<?php

namespace Modules\IncidentAttachment\Repositories;

use Modules\IncidentAttachment\Models\IncidentAttachment;
use Modules\Incident\Models\Incident;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\Helper\Helpers\EmailsHelper;

use Auth;

/**
 * Class IncidentAttachmentRepository
 *
 * Incident attachment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\IncidentAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentAttachmentRepository
{
    protected $uploadHelper;
    protected $emailsHelper;

    /**
     * Instantiate a new reposiroty instance.
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
        return IncidentAttachment::all();
    }

    /**
     * Get incident attachment by incident.
     *
     * @param Int $incident_id [Incident id]
     *
     * @return Object
     */
    public function findByIncidentId($incident_id)
    {
        $incidentAttachment = IncidentAttachment::where('incident_id', $incident_id)
            ->orderBy('id','desc')
            ->get();
        
        if ($incidentAttachment) {
            return $incidentAttachment;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create incident attachment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $incidentAttachment = new IncidentAttachment;
        // --
        // Upload attachment
        $incidentAttachment->file_hash = $this->uploadHelper->uploadAttachment(
            $input,
            'incident_attachment'
        );
        $incidentAttachment->fill($input);
        if ($incidentAttachment->save()) {
            // --
            // Send mail
            $this->emailsHelper->sendIncidentAttachmentMail(
                $logginUser,
                $incidentAttachment
            );
            // --
            // Add activities
            createUserActivity(
                IncidentAttachment::MODULE_NAME,
                $incidentAttachment->id,
                $request->method(),
                $incidentAttachment->file_name,
                $request->ip(),
                $incidentAttachment->incident_id
            );

            // Add Notification.
            $incident = Incident::where('id', $incidentAttachment->incident_id)->get()->first();
            createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Attachment Uploaded.', "#" . $incident->generated_id . ' - ' . $incidentAttachment->file_name);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete incident attachment]
     * @param int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $incidentAttachment = IncidentAttachment::findOrFail($id);
        if ($incidentAttachment->delete()) {
            // --
            // Remove file
            $files = public_path() .'/uploads/incident_attachment/'.
            $incidentAttachment->file_hash;
            if (file_exists($files)) {
                unlink($files);
            }
            // --
            // Add activities
            createUserActivity(
                IncidentAttachment::MODULE_NAME,
                $incidentAttachment->id,
                $request->method(),
                $incidentAttachment->file_name,
                $request->ip(),
                $incidentAttachment->incident_id
            );

            // Add Notification.
            $incident = Incident::where('id', $incidentAttachment->incident_id)->get()->first();
            createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Attachment Deleted.', "#" . $incident->generated_id . ' - ' . $incidentAttachment->file_name);

            return true;
        }
        return false;
    }

    /**
     * Check user permission.
     *
     * @param Int $id [Incident Attachment Id]
     *
     * @return Boolean
     */
    public function checkPermission($id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $incidentAttachment = IncidentAttachment::findOrFail($id);
        $incidentUser = Incident::where('id', $incidentAttachment->incident_id)
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
