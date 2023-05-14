<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Estimate\Models\Estimate;
use Modules\Estimate\Models\EstimateSetting;
use Modules\Helper\Helpers\EmailsHelper;

class EstimateDueReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estimate:due-reminder';
    protected $emailRepo;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Estimate Expiration Reminder Email to Customer';

    /**
     * Create a new command instance.
     *
     * @param EmailsHelper $emailRepo [Object]
     *
     * @return void
     */
    public function __construct(EmailsHelper $emailRepo)
    {
        parent::__construct();
        $this->emailRepo = $emailRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Send Estimate Expiration Reminder Email to Customer.
        $estimateSettings = EstimateSetting::select('auto_remind', 'auto_remind_before')->first();
        if ($estimateSettings->auto_remind && $estimateSettings->auto_remind_before) {
            $date = date("Y-m-d", strtotime("+".$estimateSettings->auto_remind_before." days"));
            $estimates = Estimate::with('client')->where('status', 'sent')->whereDate('valid_till', $date)->get();
            foreach ($estimates as $key => $estimate) {
                $this->emailRepo->sendEstimateExpirationReminderEmail($estimate);
            }
        }

        // Set expired status on estimate due.
        Estimate::whereDate('valid_till', '<' , date('Y-m-d'))->where('status', 'sent')->update(['status' => 'expired']);
    }
}
