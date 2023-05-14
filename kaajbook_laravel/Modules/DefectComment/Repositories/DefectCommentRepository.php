<?php

namespace Modules\DefectComment\Repositories;

use Modules\Defect\Models\Defect;
use Modules\Defect\Models\DefectHistory;
use Modules\DefectComment\Models\DefectComment;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\Helper\Helpers\EmailsHelper;

use Auth;

/**
 * Class DefectCommentRepository
 *
 * DefectComment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\DefectComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DefectCommentRepository
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
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create defect comment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $defectComment = new DefectComment;
        // --
        // Comment attachments
        if (isset($input['files'])) {
            $defectComment->attachments = $this->uploadHelper
                ->uploadMultipleAttachment(
                    $input['files'],
                    'defect_attachment'
                );
        }

        $defectComment->user_id = Auth::user()->id;
        $defectComment->fill($input);

        if ($defectComment->save()) {
            // --
            // Send mail
            $this->emailsHelper->sendDefectCommentMail($logginUser,$defectComment);
            // --
            // Add activities
            createUserActivity(
                DefectComment::MODULE_NAME,
                $defectComment->id,
                $request->method(),
                $defectComment->comment,
                $request->ip(),
                $defectComment->defect_id
            );
            // --
            // Add history
            $defect = Defect::findOrFail($defectComment->defect_id);
            if (!empty($defect)) {
                $defectHistory = new DefectHistory;
                $defectHistory->description = "Defect information commented : " .'<b>'.$defectComment->comment.'</b>';
                $defectHistory->created_by_id = $defect->create_user_id;
                $defectHistory->commented_by_id = Auth::user()->id;
                $defect->history()->save($defectHistory);
            }

            // Add Notification.
            $defect = Defect::where('id', $defectComment->defect_id)->get()->first();
            createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Comment Created.',  "#" . $defect->generated_id . ' - ' . $defectComment->comment);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource
     *
     * @param Request $request [Request for update defect comment]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $defectComment = DefectComment::findOrFail($id);
        $defectComment->comment = $input['comment'];
        if($defectComment->save()) {
            // --
            // Add activities
            createUserActivity(
                DefectComment::MODULE_NAME,
                $defectComment->id,
                $request->method(),
                $defectComment->comment,
                $request->ip(),
                $defectComment->defect_id
            );

            // Add Notification.
            $defect = Defect::where('id', $defectComment->defect_id)->get()->first();
            createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Comment Updated.',  "#" . $defect->generated_id . ' - ' . $defectComment->comment);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy delete comment]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function delete($request, $id)
    {
        $defectComment = DefectComment::findOrFail($id);
        if ($defectComment->delete()) {
            // --
            // Remove file
            if (!empty($defectComment)) {
                if (!empty($defectComment->attachments)) {
                    $this->_removeAttachments(
                        json_decode($defectComment->attachments)
                    );
                }
                // --
                // Remove child comment
                if ($defectComment->parent_id == 0) {
                    $childComments = DefectComment::where('parent_id', $id)
                        ->delete();
                }
            }
            // --
            // Add activities
            createUserActivity(
                DefectComment::MODULE_NAME,
                $defectComment->id,
                $request->method(),
                $defectComment->comment,
                $request->ip(),
                $defectComment->defect_id
            );

            // Add Notification.
            $defect = Defect::where('id', $defectComment->defect_id)->get()->first();
            createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Comment Deleted.', "#" . $defect->generated_id . ' - ' . $defectComment->comment);

            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Array $attachments [Attachments array]
     *
     * @return void
     */
    private function _removeAttachments($attachments)
    {
        foreach ($attachments as $key => $value) {
            $files = public_path() .'/uploads/defect_attachment/'. $value->file;
            if (file_exists($files)) {
                unlink($files);
            }
        }
    }

    /**
     * Check user permission.
     *
     * @param Int $id [Defect Comment Id]
     *
     * @return Boolean
     */
    public function checkPermission($id)
    {
        $user =Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $defectComment = DefectComment::findOrFail($id);
        $defectUser = Defect::where('id', $defectComment->defect_id)
            ->where(
                function ($q) {
                    $q->where('assign_member', Auth::user()->id)
                        ->orWhere('create_user_id', Auth::user()->id);
                }
            )
            ->first();

        if ($defectUser) {
            return true;
        }
        return false;
    }
}
