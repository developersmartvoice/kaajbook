<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceSetting;

class InvoiceDueReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:due-reminder';
    protected $emailRepo;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Invoice Due Reminder Email to Customer';

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
        // Send Invoice Expiration Reminder Email to Customer.
        $invoiceSettings = InvoiceSetting::select('auto_remind', 'auto_remind_after')->first();
        if ($invoiceSettings->auto_remind) {
            $days = explode(",", $invoiceSettings->auto_remind_after);
            $dates = [];
            foreach ($days as $day) {
                if($day && is_numeric($day)){
                    $dates[] = date("Y-m-d", strtotime("-".$day." days"));
                }
            }
            $invoices = Invoice::with('client')->whereIn('status', ['partially_paid', 'unpaid'])->whereIn('due_date', $dates)->get();
            foreach ($invoices as $invoice) {
                $this->emailRepo->sendInvoiceExpirationReminderEmail($invoice);
                Invoice::where('id', $invoice->id)->update([ 'last_overdue_reminder_date' => date('Y-m-d H:i:s') ]);
            }
        }
    }
}
