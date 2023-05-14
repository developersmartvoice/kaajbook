<?php

namespace Modules\IncidentComment\Repositories;

use Modules\Incident\Models\Incident;
use Modules\IncidentComment\Models\IncidentComment;
use Modules\Incident\Models\IncidentHistory;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\Helper\Helpers\EmailsHelper;

use Auth;

/**
 * Class IncidentCommentRepository
 *
 * Incident comment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\IncidentComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentCommentRepository
{
    protected $uploadHelper;
    protected $emailsHelper;

    /**
     * Instantiate a new repository instance.
     *
     * @param UploadFileHelper $uploadHelper [Object]
     * @param EmailsHelper     $emailsHelper [Object]
     *
     * @return void
     */
    public function __construct(
        UploadFileHelper $uploadHelper,
        EmailsHelper $emailsHelper
    ) {
        $this->uploadHelper = $uploadHelper;
        $this->emailsHelper = $emailsHelper;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return IncidentComment::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create incident comment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $incidentComment = new IncidentComment;
        // --
        // Comment attachments
        if (isset($input['files'])) {
            $incidentComment->attachments = $this->uploadHelper
                ->uploadMultipleAttachment(
                    $input['files'],
                    'incident_attachment'
                );
        }

        $incidentComment->user_id = Auth::user()->id;
        $incidentComment->fill($input);

        if ($incidentComment->save()) {
            // --
            // Send mail
            $this->emailsHelper->sendIncidentCommentMail(
                $logginUser,
                $incidentComment
            );
            // --
            // Add activities
            createUserActivity(
                IncidentComment::MODULE_NAME,
                $incidentComment->id,
                $request->method(),
                $incidentComment->comment,
                $request->ip(),
                $incidentComment->incident_id
            );
            // --
            // Add history
            $incident = Incident::findOrFail($incidentComment->incident_id);
            if (!empty($incident)) {
                $incidentHistory = new IncidentHistory;
                $incidentHistory->created_by_id = $incident->create_user_id;
                $incidentHistory->updated_by_id = Auth::user()->id;
                $incidentHistory->description = "Incident information commented : "
                 . '<b>'.$incidentComment->comment.'</b>';
                $incidentHistory->commented_by_id = Auth::user()->id;
                $incident->history()->save($incidentHistory);
            }

            // Add Notification.
            $incident = Incident::where('id', $incidentComment->incident_id)->get()->first();
            createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Comment Created.', "#" . $incident->generated_id . ' - ' . $incidentComment->comment);

            return true;
        } else {
            return false;
        }
    }

     /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update incident comment]
     * @param int   $id [Incident comment Id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $incidentComment = IncidentComment::findOrFail($id);
        $incidentComment->comment = $input['comment'];
        if($incidentComment->save()) {
            // --
            // Add activities
            createUserActivity(
                IncidentComment::MODULE_NAME,
                $incidentComment->id,
                $request->method(),
                $incidentComment->comment,
                $request->ip(),
                $incidentComment->incident_id
            );

            // Add Notification.
            $incident = Incident::where('id', $incidentComment->incident_id)->get()->first();
            createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Comment Updated.', "#" . $incident->generated_id . ' - ' . $incidentComment->comment);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete incident comment]
     * @param int     $id      [Row id]
     *
     * @return Object
     */
    public function delete($request, $id)
    {
        $incidentComment = IncidentComment::findOrFail($id);
        // --
        // Remove file
        if (!empty($incidentComment)) {
            if (!empty($incidentComment->attachments)) {
                $this->_removeAttachments(
                    json_decode($incidentComment->attachments)
                );
            }
            // --
            // Remove child comment
            if ($incidentComment->parent_id == 0) {
                $childComments = IncidentComment::where('parent_id', $id)->delete();
            }
        }
        // --
        // Add activities
        createUserActivity(
            IncidentComment::MODULE_NAME,
            $incidentComment->id,
            $request->method(),
            $incidentComment->comment,
            $request->ip(),
            $incidentComment->incident_id
        );

        if($incidentComment->delete()){

            // Add Notification.
            $incident = Incident::where('id', $incidentComment->incident_id)->get()->first();
            createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Comment Deleted.', "#" . $incident->generated_id . ' - ' . $incidentComment->comment);
       
            return true;
        }else{
            return false;
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param array $attachments [Attachments array]
     *
     * @return void
     */
    private function _removeAttachments($attachments)
    {
        foreach ($attachments as $key => $value) {
            $files = public_path() .'/uploads/incident_attachment/'. $value->file;
            if (file_exists($files)) {
                unlink($files);
            }
        }
    }

    /**
     * Check user permission.
     *
     * @param Int $id [Incident Comment Id]
     *
     * @return Boolean
     */
    public function checkPermission($id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $incidentComment = IncidentComment::findOrFail($id);
        $incidentUser = Incident::where('id', $incidentComment->incident_id)
            ->where(
                function ($q) {
                    $q->where('assign_to', Auth::user()->id)
                        ->orWhere('create_user_id', Auth::user()->id);
                }
            )
            ->first();

        if ($incidentUser) {
            return true;
        }
        return false;
    }
}
