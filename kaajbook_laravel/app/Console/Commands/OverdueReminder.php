<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Defect\Models\Defect;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Incident\Models\Incident;
use Modules\Projects\Models\Project;
use Modules\Task\Models\Task;

class OverdueReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overdue:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send project, task, defect, and incident overdue reminder';

    /**
     * Create a new command instance.
     *
     * @param EmailsHelper $emailsHelper [Object]
     *
     * @return void
     */
    public function __construct(EmailsHelper $emailsHelper)
    {
        parent::__construct();
        $this->emailsHelper = $emailsHelper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::now()->format('Y-m-d');

        // Project overdue reminder.
        $projects = Project::with([
            'users' => function ($query) {
                $query->where('is_active', true)->wherePivot('edit', true);
            }
        ])
        ->whereDate('end_date', '<', $today)->whereNotIn('status', [4, 5])->get();
        foreach ($projects as $project) {
            $this->emailsHelper->sendProjectOverdueReminderEmails($project);
        }

        // Task overdue reminder.
        $tasks = Task::whereDate('task_end_date', '<', $today)->whereNotIn('status', [5, 6])->get();
        foreach ($tasks as $task) {
            $this->emailsHelper->sendTaskOverdueReminderEmails($task);
        }

        // Defect overdue reminder.
        $defects = Defect::whereDate('end_date', '<', $today)->whereNotIn('status', [2, 5, 7])->get();
        foreach ($defects as $defect) {
            $this->emailsHelper->sendDefectOverdueReminderEmails($defect);
        }

        // Incident overdue reminder.
        $incidents = Incident::whereDate('end_date', '<', $today)->whereNotIn('status', [4, 5, 7])->get();
        foreach ($incidents as $incident) {
            $this->emailsHelper->sendIncidentOverdueReminderEmails($incident);
        }
    }
}
