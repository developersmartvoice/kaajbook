<?php
namespace Modules\TaskComment\Repositories;

use Modules\TaskComment\Models\TaskComment;
use Modules\Task\Models\Task;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\Helper\Helpers\EmailsHelper;

use Auth;

/**
 * Class TaskCommentRepository
 *
 * The Repository is Defined for Task Comment.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\TaskComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TaskCommentRepository
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
     * @param Request $request [Request for create task comment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $taskComment = new TaskComment;

        // --
        // Comment attachments
        if (isset($input['files'])) {
            $taskComment->attachments = $this->uploadHelper
                ->uploadMultipleAttachment(
                    $input['files'],
                    'task_attachment'
                );
        }

        $taskComment->user_id = Auth::user()->id;
        $taskComment->fill($input);
        if ($taskComment->save()) {
            // --
            // Send comment mail
            $this->emailsHelper->sendTaskCommentMail($logginUser, $taskComment);
            // --
            // Add activities
            createUserActivity(
                TaskComment::MODULE_NAME,
                $taskComment->id,
                $request->method(),
                $taskComment->comment,
                $request->ip(),
                $taskComment->task_id
            );

            // Add Notification.
            $task = Task::where('id', $taskComment->task_id)->get()->first();
            createNotification([$task->assignUser], 'tasks', $task->id, 'Task Comment Created.',  "#" . $task->generated_id . ' - ' . $taskComment->comment);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource
     *
     * @param Request $request [Request for update task comment]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $taskComment = TaskComment::findOrFail($id);
        $taskComment->comment = $input['comment'];
        if($taskComment->save()) {
            // --
            // Add activities
            createUserActivity(
                TaskComment::MODULE_NAME,
                $taskComment->id,
                $request->method(),
                $taskComment->comment,
                $request->ip(),
                $taskComment->task_id
            );

            // Add Notification.
            $task = Task::where('id', $taskComment->task_id)->get()->first();
            createNotification([$task->assignUser], 'tasks', $task->id, 'Task Comment Updated.', $taskComment->comment);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete task comment]
     * @param int     $id      [Row id]
     *
     * @return Object
     */
    public function delete($request, $id)
    {
        $taskComment = TaskComment::findOrFail($id);
        if ($taskComment->delete()) {
            // --
            // Remove file
            if (!empty($taskComment)) {
                if (!empty($taskComment->attachments)) {
                    $this->_removeAttachments(
                        json_decode($taskComment->attachments)
                    );
                }
                // --
                // Remove child comment
                if ($taskComment->parent_id == 0) {
                    $childComments = TaskComment::where('parent_id', $id)->delete();
                }
            }
            // --
            // Add activities
            createUserActivity(
                TaskComment::MODULE_NAME,
                $taskComment->id,
                $request->method(),
                $taskComment->comment,
                $request->ip(),
                $taskComment->task_id
            );
            
            // Add Notification.
            $task = Task::where('id', $taskComment->task_id)->get()->first();
            createNotification([$task->assignUser], 'tasks', $task->id, 'Task Comment Deleted.',  "#" . $task->generated_id . ' - ' . $taskComment->comment);

            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param array $attachments [Attachments array]
     *
     * @return Object
     */
    private function _removeAttachments($attachments)
    {
        foreach ($attachments as $key => $value) {
            $files = public_path() .'/uploads/task_attachment/'. $value->file;
            if (file_exists($files)) {
                unlink($files);
            }
        }
    }

    /**
     * Check user permission.
     *
     * @param Int $id [Task Comment Id]
     *
     * @return Boolean
     */
    public function checkPermission($id)
    {
        $user =Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $taskComment = TaskComment::findOrFail($id);
        $taskUser = Task::where('id', $taskComment->task_id)
            ->where(function ($q) {
                $q->where('assign_to', Auth::user()->id)
                    ->orWhere('created_by', Auth::user()->id);
            })->first();

        if ($taskUser) {
            return true;
        }
        return false;
    }
}
