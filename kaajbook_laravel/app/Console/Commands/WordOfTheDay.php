<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Helper\Helpers\CommonHelper;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Setting\Models\Setting;
use Modules\Task\Models\Task;
use Modules\Timesheet\Models\Timesheet;

class WordOfTheDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:day';
    protected $emailRepo;
    protected $commonHelper;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Daily email to users with daily tasks';

    /**
     * Create a new command instance.
     *
     * @param EmailsHelper $emailRepo [Emails Helper]
     * @param CommonHelper $commonHelper [Common Helper]
     *
     * @return void
     */
    public function __construct(EmailsHelper $emailRepo, CommonHelper $commonHelper)
    {
        parent::__construct();
        $this->emailRepo = $emailRepo;
        $this->commonHelper = $commonHelper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timesheet_table = config('core.acl.timesheets_table');
        $projects_table = config('core.acl.projects_table');
        $task_table = config('core.acl.task_table');
        $defects_table = config('core.acl.defects_table');
        $incidents_table = config('core.acl.incidents_table');
        $meeting_table = config('core.acl.meetings');
        $leaves_table = config('core.acl.leaves_table');
        $front_url = config('app.front_url');

        $task_status = [
            1 => trans('messages.status.not_started'),
            2 => trans('messages.status.in_progress'),
            3 => trans('messages.status.on_hold'),
            4 => trans('messages.status.waiting'),
            5 => trans('messages.status.cancel'),
            6 => trans('messages.status.completed')
        ];

        $defect_status = [
            1 => trans('messages.status.assigned'),
            2 => trans('messages.status.closed'),
            3 => trans('messages.status.in_progress'),
            4 => trans('messages.status.open'),
            5 => trans('messages.status.solved'),
            6 => trans('messages.status.reopen'),
            7 => trans('messages.status.deferred')
        ];

        $incident_status = [
            1 => trans('messages.status.open'),
            2 => trans('messages.status.assigned'),
            3 => trans('messages.status.in_progress'),
            4 => trans('messages.status.solved'),
            5 => trans('messages.status.deferred'),
            6 => trans('messages.status.reopen'),
            7 => trans('messages.status.closed')
        ];

        $meeting_status = [
            1 => trans('messages.status.open'),
            2 => trans('messages.status.in_progress'),
            3 => trans('messages.status.cancel'),
            4 => trans('messages.status.completed')
        ];

        $leave_status = [
            2 => trans('messages.status.approved'),
            5 => trans('messages.status.Taken')
        ];

        // $data = Timesheet::selectRaw('created_user_id,TIME_FORMAT(time(sum(TIMEDIFF( end_time, start_time ))),"%H:%i") as total')
        //     ->whereDate(\DB::raw("(DATE_FORMAT(start_time,'%Y-%m-%d'))"), '2020-05-28')
        //     ->groupBy('created_user_id')
        //     ->get();

        // $user_hours = [];
        // foreach ($data as $value) {
        //     $user_hours[$value->created_user_id] = $value->total;
        // }

        $data = Timesheet::selectRaw('created_user_id,sum(decimal_time) as total_decimal_time')
            ->whereDate(\DB::raw("(DATE_FORMAT(start_time,'%Y-%m-%d'))"), date('Y-m-d'))
            ->groupBy('created_user_id')
            ->get();

        $user_hours = [];
        foreach ($data as $value) {
            $user_hours[$value->created_user_id] = $this->commonHelper->convertDecimalToTime($value->total_decimal_time);
        }

        $timesheets = Timesheet::with([
            'user' => function ($query) {
                $query->select('id', 'firstname', 'lastname');
            }]
        )
        ->select(
            $timesheet_table.'.*',
            $projects_table.'.project_name',
            $task_table.'.name as task_name',
            $defects_table.'.defect_name',
            $incidents_table.'.incident_name',
            $meeting_table.'.title as meeting_title',
            $leaves_table.'.reason as leave_title',
            $projects_table.'.generated_id as project_generated_id',
            $task_table.'.generated_id as task_generated_id',
            $defects_table.'.generated_id as defect_generated_id',
            $incidents_table.'.generated_id as incident_generated_id',
            $meeting_table.'.generated_id as meeting_generated_id',
            $task_table.'.status as task_status',
            $defects_table.'.status as defect_status',
            $incidents_table.'.status as incident_status',
            $meeting_table.'.status as meeting_status',
            $leaves_table.'.status as leave_status'
        )
        ->leftjoin($projects_table, $projects_table.'.id', '=', $timesheet_table.'.project_id')
        ->leftjoin($task_table, $task_table.'.id', '=', $timesheet_table.'.module_related_id')
        ->leftjoin($defects_table, $defects_table.'.id', '=', $timesheet_table.'.module_related_id')
        ->leftjoin($incidents_table, $incidents_table.'.id', '=', $timesheet_table.'.module_related_id')
        ->leftjoin($meeting_table, $meeting_table.'.id', '=', $timesheet_table.'.module_related_id')
        ->leftjoin($leaves_table, $leaves_table.'.id', '=', $timesheet_table.'.module_related_id')
        ->whereDate(\DB::raw("(DATE_FORMAT($timesheet_table.start_time,'%Y-%m-%d'))"), date('Y-m-d'))
        ->orderBy($timesheet_table.'.created_user_id')
        ->get();

        $settings = Setting::select('daily_report_emails')->first();
        $emails = explode(',', $settings->daily_report_emails);
        foreach ($emails as $key => $value) {
            $mailsConfig = [
                'email'=> $value,
                'name'=> '',
                'from'=> 'no@replay.com',
                'subject'=> 'Daily Report'
            ];
            $this->emailRepo->sendmail(
                'emails.timesheet_report_template',
                [
                    'timesheets' => $timesheets,
                    'user_hours' => $user_hours,
                    'task_status' => $task_status,
                    'defect_status' => $defect_status,
                    'incident_status' => $incident_status,
                    'meeting_status' => $meeting_status,
                    'leave_status' => $leave_status,
                    'front_url' => $front_url,
                    'app_name' => config('app.name')
                ],
                $mailsConfig
            );
        }
    }
}
