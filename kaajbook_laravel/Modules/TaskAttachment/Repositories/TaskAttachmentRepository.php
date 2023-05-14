<?php
namespace Modules\TaskAttachment\Repositories;

use Auth;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\TaskAttachment\Models\TaskAttachment;
use Modules\Task\Models\Task;

/**
 * Class TaskAttachmentRepository
 *
 * TaskAttachment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\TaskAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TaskAttachmentRepository
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
     * Get task attachment by task.
     *
     * @param Int $task_id [Task id]
     *
     * @return Object
     */
    public function findByTaskId($task_id)
    {
        $taskAttachment = TaskAttachment::where('task_id', $task_id)
            ->orderBy('id', 'desc')
            ->get();

        if ($taskAttachment) {
            return $taskAttachment;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create task attachment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $taskAttachment = new TaskAttachment;

        // --
        // Upload attachment
        $taskAttachment->file_hash = $this->uploadHelper->uploadAttachment(
            $input,
            'task_attachment'
        );
        $taskAttachment->fill($input);
        if ($taskAttachment->save()) {
            // --
            // Send mail
            $this->emailsHelper->sendTaskAttachmentMail(
                $logginUser,
                $taskAttachment
            );
            // --
            // Add activities
            createUserActivity(
                TaskAttachment::MODULE_NAME,
                $taskAttachment->id,
                $request->method(),
                $taskAttachment->file_name,
                $request->ip(),
                $taskAttachment->task_id
            );

            // Add Notification.
            $task = Task::where('id', $taskAttachment->task_id)->get()->first();
            createNotification([$task->assignUser], 'tasks', $task->id, 'Task Attachment Uploaded.',  "#" . $task->generated_id . ' - ' . $taskAttachment->file_name);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy task attachment]
     * @param int     $id      [Row id]
     *
     * @return Object
     */
    public function delete($request, $id)
    {
        $taskAttachment = TaskAttachment::findOrFail($id);

        // --
        // Remove file
        $files = public_path() . '/uploads/task_attachment/' .
        $taskAttachment->file_hash;
        if (file_exists($files)) {
            unlink($files);
        }

        // --
        // Add activities
        createUserActivity(
            TaskAttachment::MODULE_NAME,
            $taskAttachment->id,
            $request->method(),
            $taskAttachment->file_name,
            $request->ip(),
            $taskAttachment->task_id
        );

        if ($taskAttachment->delete()) {

            // Add Notification.
            $task = Task::where('id', $taskAttachment->task_id)->get()->first();
            createNotification([$task->assignUser], 'tasks', $task->id, 'Task Attachment Deleted.',  "#" . $task->generated_id . ' - ' . $taskAttachment->file_name);

            return true;

        } else {
            return false;
        }
    }

    /**
     * Check user permission.
     *
     * @param Int $id [Task Attachment Id]
     *
     * @return Boolean
     */
    public function checkPermission($id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $taskAttachment = TaskAttachment::findOrFail($id);
        $taskUser = Task::where('id', $taskAttachment->task_id)
            ->where(function ($q) {
                $q->where('assign_to', Auth::user()->id)
                    ->orWhere('created_by', Auth::user()->id);
            }
            )->first();

        if ($taskUser) {
            return true;
        }
        return false;
    }
}
