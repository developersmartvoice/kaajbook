<?php

namespace Modules\DefectAttachment\Repositories;

use Modules\DefectAttachment\Models\DefectAttachment;
use Modules\Defect\Models\Defect;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\Helper\Helpers\EmailsHelper;

use Auth;

/**
 * Class DefectAttachmentRepository
 *
 * Defect attachment create and delete.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\DefectAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DefectAttachmentRepository
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
     * Get defect attachment by defect.
     *
     * @param Int $defect_id [Defect id]
     *
     * @return Object
     */
    public function findByDefectId($defect_id)
    {
        $defectAttachment = DefectAttachment::where('defect_id', $defect_id)
            ->orderBy('id','desc')
            ->get();
        
        if ($defectAttachment) {
            return $defectAttachment;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create defect attachment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $defectAttachment = new DefectAttachment;
        // --
        // Upload attachment
        $defectAttachment->file_hash = $this->uploadHelper->uploadAttachment(
            $input,
            'defect_attachment'
        );
        $defectAttachment->fill($input);
        if ($defectAttachment->save()) {
            // --
            // Send mail
            $this->emailsHelper->sendDefectAttachmentMail(
                $logginUser,
                $defectAttachment
            );
            // --
            // Add activities
            createUserActivity(
                DefectAttachment::MODULE_NAME,
                $defectAttachment->id,
                $request->method(),
                $defectAttachment->file_name,
                $request->ip(),
                $defectAttachment->defect_id
            );

            // Add Notification.
            $defect = Defect::where('id', $defectAttachment->defect_id)->get()->first();
            createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Attachment Uploaded.', "#" . $defect->generated_id . ' - ' .$defectAttachment->file_name);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete defect attachment]
     * @param int     $id      [Row id]
     *
     * @return Object
     */
    public function delete($request, $id)
    {
        $defectAttachment = DefectAttachment::findOrFail($id);
        if ($defectAttachment->delete()) {
            // --
            // Remove file
            $files = public_path() .'/uploads/defect_attachment/'.
            $defectAttachment->file_hash;
            if (file_exists($files)) {
                unlink($files);
            }
            // --
            // Add activities
            createUserActivity(
                DefectAttachment::MODULE_NAME,
                $defectAttachment->id,
                $request->method(),
                $defectAttachment->file_name,
                $request->ip(),
                $defectAttachment->defect_id
            );

            // Add Notification.
            $defect = Defect::where('id', $defectAttachment->defect_id)->get()->first();
            createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Attachment Deleted.',  "#" . $defect->generated_id . ' - ' . $defectAttachment->file_name);

            return true;
        }
        return false;
    }

    /**
     * Check user permission.
     *
     * @param Int $id [Defect Attachment Id]
     *
     * @return Boolean
     */
    public function checkPermission($id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $defectAttachment = DefectAttachment::findOrFail($id);
        $defectUser = Defect::where('id', $defectAttachment->defect_id)
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
