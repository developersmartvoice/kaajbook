<?php

namespace Modules\ProjectComment\Repositories;

use Auth;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\ProjectComment\Models\ProjectComment;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\ProjectUser;

/**
 * Class ProjectCommentRepository
 *
 * ProjectComment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectCommentRepository
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
     * @return object
     */
    public function findAll()
    {
        return ProjectComment::with('user')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create project comment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $projectcomment = new ProjectComment;

        // --
        // Comment attachments
        if (isset($input['files'])) {
            $projectcomment->attachments = $this->uploadHelper
                ->uploadMultipleAttachment(
                    $input['files'],
                    'project_attachment'
                );
        }

        $projectcomment->user_id = Auth::user()->id;
        $projectcomment->fill($input);
        if ($projectcomment->save()) {
            // --
            // Send comment mail.
            $this->emailsHelper->sendProjectCommentMail(
                $logginUser,
                $projectcomment
            );

            // Add Notification.
            $project = Project::select('id', 'generated_id')->find($projectcomment->project_id);
            $this->createNotification($projectcomment->project_id, "Project Comment Added.", "#" . $project->generated_id . ' - ' . $projectcomment->comment);

            // --
            // Add activities
            createUserActivity(
                ProjectComment::MODULE_NAME,
                $projectcomment->id,
                $request->method(),
                $projectcomment->comment,
                $request->ip(),
                $projectcomment->project_id
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource
     *
     * @param Request $request [Request for update project comment]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $projectComment = ProjectComment::findOrFail($id);
        $projectComment->comment = $input['comment'];
        if ($projectComment->save()) {
            // --
            // Add activities
            createUserActivity(
                ProjectComment::MODULE_NAME,
                $projectComment->id,
                $request->method(),
                $projectComment->comment,
                $request->ip(),
                $projectComment->project_id
            );

            // Add Notification.
            $project = Project::select('id', 'generated_id')->find($projectComment->project_id);
            $this->createNotification($projectComment->project_id, "Project Comment Updated.", "#" . $project->generated_id . ' - ' . $projectComment->comment);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete project comment]
     * @param int     $id      [Row id]
     *
     * @return Boolen
     */
    public function delete($request, $id)
    {
        $projectcomment = ProjectComment::findOrFail($id);

        // Add Notification.
        $project = Project::select('id', 'generated_id')->find($projectcomment->project_id);
        $this->createNotification($projectcomment->project_id, "Project Comment Deleted.", "#" . $project->generated_id . ' - ' . $projectcomment->comment);

        if ($projectcomment->delete()) {
            // --
            // Remove file.
            if (!empty($projectcomment)) {
                if (!empty($projectcomment->attachments)) {
                    $this->_removeAttachments(
                        json_decode($projectcomment->attachments)
                    );
                }
                // --
                // Remove child comment.
                if ($projectcomment->parent_id == 0) {
                    $childComments = ProjectComment::where('parent_id', $id)
                        ->delete();
                }
            }
            // --
            // Add activities
            createUserActivity(
                ProjectComment::MODULE_NAME,
                $projectcomment->id,
                $request->method(),
                $projectcomment->comment,
                $request->ip(),
                $projectcomment->project_id
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the attachments from storage.
     *
     * @param array $attachments [Attachments array]
     *
     * @return void
     */
    private function _removeAttachments($attachments)
    {
        foreach ($attachments as $key => $value) {
            $files = public_path() . '/uploads/project_attachment/' . $value->file;
            if (file_exists($files)) {
                unlink($files);
            }
        }
    }

    /**
     * Check user permission.
     *
     * @param Int    $id [Project Comment Id]
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

        $projectcomment = ProjectComment::findOrFail($id);
        $projectUser = ProjectUser::where('project_id', $projectcomment->project_id)
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
