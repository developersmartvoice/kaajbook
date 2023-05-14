<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Modules\Appointments\Models\Appointment;
use Modules\Appointments\Models\AppointmentRecurrence;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Meeting\Models\Meeting;
use Modules\Setting\Models\Setting;

class StartReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:reminder';
    protected $emailsHelper;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send meeting and appointment start reminder';

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
        $setting = Setting::select('reminder_alert_before_hours')->first();
        $beforeHours = explode(',', $setting->reminder_alert_before_hours);

        foreach ($beforeHours as $hours) {
            $reminderDateTime = Carbon::now()->addHours($hours);
            
            // Send meeting reminder.
            $meetings = Meeting::where(DB::raw("(DATE_FORMAT(start_date,'%Y-%m-%d %H:%i'))"), $reminderDateTime->format('Y-m-d H:i'))
                ->get();

            foreach ($meetings as $meeting) {
                $this->emailsHelper->sendMeetingReminderEmails($meeting);
            }

            // Send appointment reminder.
            $appointments = Appointment::where(DB::raw("(DATE_FORMAT(start_date_time,'%Y-%m-%d %H:%i'))"), $reminderDateTime->format('Y-m-d H:i'))
                ->get();

            foreach ($appointments as $appointment) {
                $this->emailsHelper->sendAppointmentReminderEmails($appointment);
            }

            // Send appointment recurrence reminder.
            $appointmentRecurrences = AppointmentRecurrence::where(DB::raw("(DATE_FORMAT(start_date_time,'%Y-%m-%d %H:%i'))"), $reminderDateTime->format('Y-m-d H:i'))
                ->get();

            foreach ($appointmentRecurrences as $appointmentRecurrence) {
                $appointment = Appointment::where('id', $appointmentRecurrence->appointment_id)->first();
                $appointment->start_date_time = $appointmentRecurrence->start_date_time;
                $appointment->end_date_time = $appointmentRecurrence->end_date_time;
                $this->emailsHelper->sendAppointmentReminderEmails($appointment);
            }
        }
    }
}
