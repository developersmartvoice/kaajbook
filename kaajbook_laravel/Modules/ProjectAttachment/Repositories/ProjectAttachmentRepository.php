<?php
namespace Modules\ProjectAttachment\Repositories;

use Auth;
use Modules\Projects\Models\Project;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Projects\Models\ProjectUser;

use Modules\Helper\Helpers\UploadFileHelper;
use Modules\ProjectAttachment\Models\ProjectAttachment;

/**
 * Class ProjectAttachmentRepository
 *
 * ProjectAttachment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectAttachmentRepository
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
     * Get project attachment by project.
     *
     * @param Int $project_id [Project id]
     *
     * @return Object
     */
    public function findByProjectId($project_id)
    {
        $projectAttachment = ProjectAttachment::where('project_id', $project_id)
            ->orderBy('id','desc')
            ->get();
        
        if ($projectAttachment) {
            return $projectAttachment;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create project attachment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $projectAttachment = new ProjectAttachment;

        // --
        // Upload attachment
        $projectAttachment->file_hash = $this->uploadHelper->uploadAttachment(
            $input,
            'project_attachment'
        );
        $projectAttachment->fill($input);

        if ($projectAttachment->save()) {
            // --
            // Send mail
            $this->emailsHelper->sendProjectAttachmentMail(
                $logginUser,
                $projectAttachment
            );

            // Add Notification.
            $project = Project::select('id', 'generated_id')->find($projectAttachment->project_id);
            $this->createNotification($projectAttachment->project_id, "Project Attachment Added.", "#" . $project->generated_id . ' - ' . $projectAttachment->file_name);
            
            // --
            // Add activities
            createUserActivity(
                ProjectAttachment::MODULE_NAME,
                $projectAttachment->id,
                $request->method(),
                $projectAttachment->file_name,
                $request->ip(),
                $projectAttachment->project_id
            );
            
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete project attachment]
     * @param int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $projectAttachment = ProjectAttachment::findOrFail($id);

        // Add Notification.
        $project = Project::select('id', 'generated_id')->find($projectAttachment->project_id);
        $this->createNotification($projectAttachment->project_id, "Project Attachment Deleted.", "#" . $project->generated_id . ' - ' . $projectAttachment->file_name);

        if ($projectAttachment->delete()) {
            // --
            // Remove file
            $files = public_path() .'/uploads/project_attachment/'.
            $projectAttachment->file_hash;
            if (file_exists($files)) {
                unlink($files);
            }
            // --
            // Add activities
            createUserActivity(
                ProjectAttachment::MODULE_NAME,
                $projectAttachment->id,
                $request->method(),
                $projectAttachment->file_name,
                $request->ip(),
                $projectAttachment->project_id
            );
            return true;
        }
        return false;
    }

    /**
     * Check user permission.
     *
     * @param Int    $id [Project Attachment Id]
     * @param String $action     [Action For]
     *
     * @return Boolean
     */
    public function checkPermission($id, $action)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $projectAttachment = ProjectAttachment::findOrFail($id);
        $projectUser = ProjectUser::where('project_id', $projectAttachment->project_id)
            ->where('user_id', Auth::user()->id)
            ->where($action, true)
            ->first();

        if ($projectUser) {
            return true;
        }
        return false;
    }

    /**
     * Create notification.
     *
     * @param Int    $id [Project Id]
     * @param String $title     [Title]
     * @param String $message     [Message]
     *
     * @return Boolean
     */
    private function createNotification($id, $title, $message)
    {
        $project = Project::with(['users' => function ($query) {
            $query->select('id')->wherePivot('edit', true);
        }])->find($id);

        createNotification($project->users, 'projects', $project->id, $title, $message);
    }
}
