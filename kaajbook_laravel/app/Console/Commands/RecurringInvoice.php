<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceItem;
use Modules\Invoice\Repositories\InvoiceRepository;
use Modules\User\Repositories\UserRepository;

class RecurringInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:invoice';
    protected $invoiceRepo;
    protected $emailsHelper;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create recurring invoice.';

    /**
     * Create a new command instance.
     *
     * @param InvoiceRepository $invoiceRepo [Invoice Repo]
     * @param EmailsHelper $emailsHelper [Emails Helper]
     *
     * @return void
     */
    public function __construct(InvoiceRepository $invoiceRepo, EmailsHelper $emailsHelper)
    {
        parent::__construct();
        $this->invoiceRepo = $invoiceRepo;
        $this->emailsHelper = $emailsHelper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $invoices = Invoice::where('recurrence', true)
            ->where('is_recurrence_generated', false)
            ->where('recurrence_occurrences', '!=', 0)
            ->where(function ($q) {

                $daily = Carbon::now()->subDay()->format('Y-m-d');
                $weekly = Carbon::now()->subWeek()->format('Y-m-d');
                $monthly = Carbon::now()->subMonth()->format('Y-m-d');
                $quarterly = Carbon::now()->subMonths(3)->format('Y-m-d');
                $semi_annually = Carbon::now()->subMonths(6)->format('Y-m-d');
                $annually = Carbon::now()->subYear()->format('Y-m-d');

                $q->where(function ($query) use ($daily) {
                    $query->where('invoice_date', '<=', $daily)->where('recurrence_pattern', '=', 'daily');
                })->orWhere(function ($query) use ($weekly) {
                    $query->where('invoice_date', '<=', $weekly)->where('recurrence_pattern', '=', 'weekly');
                })->orWhere(function ($query) use ($monthly) {
                    $query->where('invoice_date', '<=', $monthly)->where('recurrence_pattern', '=', 'monthly');
                })->orWhere(function ($query) use ($quarterly) {
                    $query->where('invoice_date', '<=', $quarterly)->where('recurrence_pattern', '=', 'quarterly');
                })->orWhere(function ($query) use ($semi_annually) {
                    $query->where('invoice_date', '<=', $semi_annually)->where('recurrence_pattern', '=', 'semi_annually');
                })->orWhere(function ($query) use ($annually) {
                    $query->where('invoice_date', '<=', $annually)->where('recurrence_pattern', '=', 'annually');
                });
            })
            ->get();

        $invoices->each(function ($invoice) {
            $this->_create($invoice);
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Object $invoice [For create invoice]
     *
     * @return Boolean
     */
    private function _create($invoice)
    {
        if ($invoice->recurrence_pattern == 'daily') {
            $invoiceDate = $invoice->invoice_date->addDay();
            $dueDate = $invoice->invoice_date->addDay();
        } elseif ($invoice->recurrence_pattern == 'weekly') {
            $invoiceDate = $invoice->invoice_date->addWeek();
            $dueDate = $invoice->invoice_date->addWeek();
        } elseif ($invoice->recurrence_pattern == 'monthly') {
            $invoiceDate = $invoice->invoice_date->addMonth();
            $dueDate = $invoice->invoice_date->addMonth();
        } elseif ($invoice->recurrence_pattern == 'quarterly') {
            $invoiceDate = $invoice->invoice_date->addMonths(3);
            $dueDate = $invoice->invoice_date->addMonths(3);
        } elseif ($invoice->recurrence_pattern == 'semi_annually') {
            $invoiceDate = $invoice->invoice_date->addMonths(6);
            $dueDate = $invoice->invoice_date->addMonths(6);
        } elseif ($invoice->recurrence_pattern == 'annually') {
            $invoiceDate = $invoice->invoice_date->addYear();
            $dueDate = $invoice->invoice_date->addYear();
        }

        $dueAfterDays = $invoice->invoice_date->diffInDays($invoice->due_date);
        $dueDate = $dueDate->addDays($dueAfterDays)->format('Y-m-d');

        $recurringInvoice = new Invoice();
        $recurringInvoice->parent_id = ($invoice->parent_id) ? $invoice->parent_id : $invoice->id;
        $recurringInvoice->user_id = $invoice->user_id;
        $recurringInvoice->client_id = $invoice->client_id;
        $recurringInvoice->project_id = $invoice->project_id;
        $recurringInvoice->estimate_id = $invoice->estimate_id;
        $recurringInvoice->invoice_number = $this->invoiceRepo->getInvoiceNumber();
        $recurringInvoice->invoice_date = $invoiceDate;
        $recurringInvoice->due_date = $dueDate;
        $recurringInvoice->sub_total = $invoice->sub_total;
        $recurringInvoice->total_tax = $invoice->total_tax;
        $recurringInvoice->discount_type = $invoice->discount_type;
        $recurringInvoice->discount = $invoice->discount;
        $recurringInvoice->total_discount = $invoice->total_discount;
        $recurringInvoice->adjustment = $invoice->adjustment;
        $recurringInvoice->total_amount = $invoice->total_amount;
        $recurringInvoice->total_due_amount = $invoice->total_amount;
        $recurringInvoice->reference = $invoice->reference;
        $recurringInvoice->note = $invoice->note;
        $recurringInvoice->recurrence = $invoice->recurrence;
        $recurringInvoice->recurrence_occurrences = ($invoice->recurrence_occurrences == '-1') ? '-1' : $invoice->recurrence_occurrences - 1;
        $recurringInvoice->recurrence_pattern = $invoice->recurrence_pattern;
        $recurringInvoice->save();

        // Invoice Item.
        foreach ($invoice->items as $item) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $recurringInvoice->id;
            $invoiceItem->item_name = $item->item_name;
            $invoiceItem->item_description = $item->item_description;
            $invoiceItem->item_unit = $item->item_unit;
            $invoiceItem->unit_price = $item->unit_price;
            $invoiceItem->quantity = $item->quantity;
            $invoiceItem->total_item_amount = $item->total_item_amount;
            $invoiceItem->save();

            // Invoice Item Tax.
            foreach ($item->taxes as $tax) {
                $invoiceItem->taxes()->attach($tax->id, ['item_tax' => $tax->pivot->item_tax]);
            }
        }

        $invoice->is_recurrence_generated = true;
        $invoice->save();

        // Add Notification.
        $admins = UserRepository::getAdminUsers();
        createNotification($admins, 'invoices', $recurringInvoice->id, 'Invoice Created.', "#" . $recurringInvoice->invoice_number);
        createNotification([$recurringInvoice->client], 'invoices', $recurringInvoice->id, 'Invoice Created.', "#" . $recurringInvoice->invoice_number);


        // Sent invoice to customer.
        $this->emailsHelper->sendInvoiceEmail($recurringInvoice);
    }
}
