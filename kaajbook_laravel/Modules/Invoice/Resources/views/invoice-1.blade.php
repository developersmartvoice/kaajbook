<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ trans('messages.invoices.title') }}</title>
    <style>
    @import"https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap";

    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    body {
        position: relative;
        width: 100%;
        height: auto;
        margin: 12% auto;
        color: #333333;
        background: #FFFFFF;
        font-size: 14px;
        font-family: 'Poppins', sans-serif !important;
    }

    table {
        width: 100%;
        border-spacing: 0;
        margin-bottom: 10px;
    }

    table th,
    table td {
        padding: 8px 10px 8px 10px;
        background: #fdfdfd;
        text-align: center;
        font-size: 14px !important;
    }

    table th {
        white-space: nowrap;
        font-weight: normal;
    }

    table td {
        text-align: right;
    }

    table .no {
        color: #FFFFFF;
        background: #009688;
        text-align: center;
    }

    .noo {
        /* color: #FFFFFF; */
        background: #BFC9CA;
     }
    table.custom-border td {
            border: .5px solid #B2BABB; /* Set the border to 1 pixel width and red color (#ff0000) */
        }

    table .desc {
        text-align: left;
    }

    table .unit {
        background: #EBF5FB;
    }

    table td.qty {
        font-size: 14px;
        text-align: center;
    }

    table tr.tax .tax {
        text-align: right;
    }

    table tr.discount .discount {
        text-align: right;
    }

    table tr.subtotal .subtotal {
        text-align: right;
        color: #000000;
    }

    table tbody tr:last-child td {
        border: none;
    }

    table tfoot td {
        background: #FFFFFF;
        font-size: 14px;
        white-space: nowrap;
        border-bottom: 1px solid #ededed;
    }

    table tfoot tr:first-child td {
        border-top: none;
    }

    table tfoot tr td:first-child {
        border: none;
    }

    thead th {
        font-size: 14px !important;
        font-weight: bold;
        padding: 10px 10px;
        border-top: 1px solid #ededed;
        border-bottom: 1px solid #ededed;
    }

    table.billing td {
        background-color: #FFFFFF;
    }

    .total td {
        font-size: 16px !important;
        font-weight: bold;
        color: #000000;
    }

    p {
        margin-top: 4px;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .item {
        color: #000000;
        font-size: 14px;
        margin: 0 0 0 0;
        font-weight: bold;
    }

    .cpy-info {
        font-size: 14px;
        letter-spacing: 0.6px;
        margin-top: 2px;
        font-weight: normal;
    }

    .status {
        /*margin-top: 15px;
        width: 125px;
        color: #FFFFFF;
        float: right;
        text-align: center;
        display: inline-block;*/
        font-weight: bolder;
        font-size: 18px !important;
    }

    .status.unpaid {
        color: #e74a25;
    }

    .status.partially_paid {
        color: #ffb136;
    }

    .status.paid {
        color: #2ecc71;
    }

    .notices {
        /*padding-left: 10px;
        border-left: 10px solid #009688;*/
    }

    .align-left {
        text-align: left;
    }

    .align-center {
        text-align: center;
    }
    .align-right {
        text-align: right;
    }
    

    .w-5 {
        width: 5%;
    }

    .w-15 {
        width: 15%;
    }

    .w-20 {
        width: 20%;
    }

    .w-40 {
        width: 40%;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .f-12 {
        font-size: 12px !important;
    }

    .logo img {
        max-width: 150px;
    }

    </style>
</head>

<body>
    <header class="clearfix">
        <table cellpadding="0" cellspacing="0" class="billing">
            <tr>
                <td class="align-left logo">
                    @if(!is_null($invoiceSetting->invoice_logo) && file_exists(public_path('uploads/invoice_logo/'.$invoiceSetting->invoice_logo)))
                        <img src="{{ asset('uploads/invoice_logo/'.$invoiceSetting->invoice_logo) }}" alt="" />
                    
                    @endif
                </td>
                <td>
                    <b>{{ strtoupper(trans('messages.invoices.title')) }}</b><br>
                    <b># {{ $invoice->invoice_number }}</b>
                </td>
            </tr>
            <tr>
                <td class="align-left">
                    <p>{{ ucfirst(trans('messages.invoices.to')) }}</p>
                    <p><b>{{ strtoupper($invoice->client->full_name) }}</b></p>
                    <p class="cpy-info">
                        @if($invoice->client->address)
                        {!! nl2br($invoice->client->address) !!}
                        @endif
                        @if($invoice->client->phone)
                            <br>{{ $invoice->client->phone }}
                        @endif
                        @if($invoice->client->mobile)
                            <br>{{ $invoice->client->mobile }}
                        @endif
                    </p>
                </td>
                <td>
                    <div class="mb-20">
                        <p><b>{{ $setting->company_name }}</b></p>
                        <p class="cpy-info">
                            {!! nl2br($setting->company_address) !!}
                            <br>{{ $setting->company_phone }}
                            @if($invoiceSetting->show_gst_number)
                            <br><!-- {{ strtoupper(trans('messages.invoices.gst_number')) }}: --> {{ strtoupper($invoiceSetting->gst_number) }}
                            @endif
                        </p>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <thead>
                <tr>
                    <td class="align-left"><b>{{ trans('messages.invoices.invoice_date') }}: </b>{{ $invoice->invoice_date->format($setting->php_date_format) }}</td>
                    <td class="align-center"><b>{{ trans('messages.invoices.due_date') }}: </b>{{ $invoice->due_date->format($setting->php_date_format) }}</td>
                    @if($invoice->reference)
                        <td class="align-right"><b>{{ trans('messages.invoices.reference') }} # </b>{{ $invoice->reference }}</td>
                    @endif
                </tr>
            </thead>
        </table>
    </header>
    <main>
        <table class="custom-border" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="w-5 noo">#</th>
                    <th class="w-40 noo">{{ trans('messages.invoices.item_name') }}</th>
                    <th class="w-5 qty noo">{{ trans('messages.invoices.quantity') }}</th>
                    <th class="w-15 noo">{{ trans('messages.invoices.unit_price') }}</th>
                    <th class="w-15 noo">{{ trans('messages.invoices.tax') }}</th>
                    <th class="w-20 unit">{{ trans('messages.invoices.total_item_amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                $count = 0;
                $taxes = [];
                @endphp
                @foreach($invoice->items as $item)
                <tr style="page-break-inside: avoid;">
                    <td class="w-5 no">{{ ++$count }}</td>
                    <td class="w-40 desc">
                        <div class="item">{{ ucfirst($item->item_name) }}</div>
                        <div class="f-12">{!! $item->item_description !!}</div>
                    </td>
                    <td class="w-5 qty">
                        {{ $item->quantity }}
                        <div class="f-12">{{ $item->item_unit }}</div>
                    </td>
                    <td class="w-15">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="w-15">
                        @foreach($item->taxes as $tax)
                        {{ $tax->tax_name }} {{ $tax->tax_rate }} % <br>
                        @php
                        if(array_key_exists($tax->pivot->tax_id, $taxes)){
                        $total_tax = $taxes[$tax->pivot->tax_id]['total_tax'] + $tax->pivot->item_tax;
                        }else{
                        $total_tax = $tax->pivot->item_tax;
                        }
                        $taxes[$tax->pivot->tax_id] = [
                        'tax_name' => $tax->tax_name,
                        'tax_rate' => $tax->tax_rate,
                        'total_tax' => $total_tax,
                        ];
                        @endphp
                        @endforeach
                    </td>
                    <td class="w-20 unit">{{ number_format($item->total_item_amount, 2) }}</td>
                </tr>
                @endforeach
                <tr style="page-break-inside: avoid;" class="subtotal">
                    <td class="w-5 no">&nbsp;</td>
                    <td class="w-40 desc">&nbsp;</td>
                    <td class="w-5 qty">&nbsp;</td>
                    <td class="w-15">&nbsp;</td>
                    <td class="w-15 subtotal">{{ trans('messages.invoices.sub_total') }}</td>
                    <td class="w-20 unit">{{ number_format($invoice->sub_total, 2) }}</td>
                </tr>
                @foreach($taxes as $tax)
                <tr style="page-break-inside: avoid;" class="tax">
                    <td class="w-5 no">&nbsp;</td>
                    <td class="w-40 desc">&nbsp;</td>
                    <td class="w-5 qty">&nbsp;</td>
                    <td class="w-15">&nbsp;</td>
                    <td class="w-15 tax">{{ $tax['tax_name'] }} {{ $tax['tax_rate'] }} %</td>
                    <td class="w-20 unit">{{ number_format($tax['total_tax'], 2) }}</td>
                </tr>
                @endforeach
                @if($invoice->total_discount)
                <tr style="page-break-inside: avoid;" class="discount">
                    <td class="w-5 no">&nbsp;</td>
                    <td class="w-40 desc">&nbsp;</td>
                    <td class="w-5 qty">&nbsp;</td>
                    <td class="w-15">&nbsp;</td>
                    <td class="w-15 discount">{{ trans('messages.invoices.discount') }}</td>
                    <td class="w-20 unit">-{{ number_format($invoice->total_discount, 2) }}</td>
                </tr>
                @endif
                @if($invoice->adjustment)
                <tr style="page-break-inside: avoid;" class="discount">
                    <td class="w-5 no">&nbsp;</td>
                    <td class="w-40 desc">&nbsp;</td>
                    <td class="w-5 qty">&nbsp;</td>
                    <td class="w-15">&nbsp;</td>
                    <td class="w-15">{{ trans('messages.invoices.adjustment') }}</td>
                    <td class="w-20 unit">{{ number_format($invoice->adjustment, 2) }}</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr dontbreak="true" class="total">
                    <td colspan="5">{{ trans('messages.invoices.total') }}</td>
                    <td>TK {{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                @if(!$invoice->payments->isEmpty())
                <tr class="total">
                    <td colspan="5">{{ trans('messages.invoices.due') }}</td>
                    <td>TK {{ number_format($invoice->total_due_amount, 2) }}</td>
                </tr>
                @endif
            </tfoot>
        </table>
        @if(!$invoice->payments->isEmpty())
            <h4>{{ trans('messages.invoices.payments') }} : </h4>
            <table border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>{{ trans('messages.invoices.receipt_no') }} #</th>
                        <th>{{ trans('messages.invoices.payment_method') }}</th>
                        <th>{{ trans('messages.invoices.date') }}</th>
                        <th>{{ trans('messages.invoices.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                        @if($payment->status == 'successful')
                        <tr style="page-break-inside: avoid;">
                            <td class="align-center">{{ $payment->receipt_number }}</td>
                            <td class="align-center">{{ $payment->payment_method }}</td>
                            <td class="align-center">{{ $payment->date->format($setting->php_date_time_format) }}</td>
                            <td>TK {{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <br>
        @endif
        @if(!$paymentMethods->isEmpty())
            <h4>{{ trans('messages.invoices.offline_payment') }} : </h4>
            <table border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        @foreach($paymentMethods as $method)
                        <td class="align-left">
                            <b><span>{{ $method->method_name }}</span></b><br>
                            <span>{!! nl2br($method->description) !!}</span>
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @endif
        @if($invoiceSetting->client_note)
            <div class="notices"><b>{{ trans('messages.invoices.note') }}: </b><br><span>{{ $invoiceSetting->client_note }}</span></div>
        @endif
        @if($invoiceSetting->terms_conditions)
            <div class="notices"><b>{{ trans('messages.invoices.terms_conditions') }}: </b><br><span>{{ $invoiceSetting->terms_conditions }}</span></div>
        @endif
    </main>
</body>

</html>
