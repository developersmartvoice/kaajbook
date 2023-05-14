<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ trans('messages.payments.title') }}</title>
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
        margin: 0 auto;
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

    p {
        margin-top: 4px;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .cpy-info {
        font-size: 14px;
        letter-spacing: 0.6px;
        margin-top: 2px;
        font-weight: normal;
    }

    .status {
        /*margin-top: 15px;
        width: 100px;
        color: #FFFFFF;
        float: right;
        text-align: center;
        display: inline-block;*/
        font-weight: bolder;
        font-size: 18px !important;
    }

    .status.in_progress {
        color: #ffb136;
    }

    .status.failed {
        color: #e74a25;
    }

    .status.successful {
        color: #2ecc71;
    }

    .align-left {
        text-align: left;
    }

    .align-center {
        text-align: center;
    }

    .mb-20 {
        margin-bottom: 20px;
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
                        <img src="{{ asset('uploads/invoice_logo/'.$invoiceSetting->invoice_logo) }}" alt="Logo" />
                    @else
                        <img src="{{ asset('img/favicon/vipspm-dark-logo.png') }}" alt="Logo" />
                    @endif
                </td>
                <td>
                    <b>{{ strtoupper(trans('messages.payments.title')) }}</b><br>
                    <b># {{ $payment->receipt_number }}</b><br>
                    <div class="status {{ $payment->status }}">
                        @if ($payment->status == 'in_progress')
                            {{ strtoupper(trans('messages.payments.status.in_progress')) }}
                        @elseif ($payment->status == 'successful')
                            {{ strtoupper(trans('messages.payments.status.successful')) }}
                        @elseif ($payment->status == 'failed')
                            {{ strtoupper(trans('messages.payments.status.failed')) }}
                        @else
                            {{ strtoupper(str_replace('_', ' ', $payment->status)) }}
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-left">
                    <p>{{ ucfirst(trans('messages.payments.to')) }}</p>
                    <p><b>{{ strtoupper($payment->client->full_name) }}</b></p>
                    <p class="cpy-info">
                        @if($payment->client->address)
                        {!! nl2br($payment->client->address) !!}
                        @endif
                        @if($payment->client->phone)
                            <br>{{ $payment->client->phone }}
                        @endif
                        @if($payment->client->mobile)
                            <br>{{ $payment->client->mobile }}
                        @endif
                    </p>
                </td>
                <td>
                    <div class="mb-20">
                        <p><b>{{ $setting->company_name }}</b></p>
                        <p class="cpy-info">
                            {!! nl2br($setting->company_address) !!}
                            <br>{{ $setting->company_phone }}
                        </p>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <thead>
                <tr>
                    <td class="align-center"><b>{{ trans('messages.payments.payment_date') }}: </b>{{ $payment->date->format($setting->php_date_time_format) }}</td>
                    <td class="align-center"><b>{{ trans('messages.payments.payment_method') }}: </b>{{ $payment->payment_method }}</td>
                </tr>
                @if($payment->transaction_id)
                    <tr>
                        <td class="align-center"><b>{{ trans('messages.payments.transaction_id') }}: </b>{{ $payment->transaction_id }}</td>
                    </tr>
                @endif
            </thead>
        </table>
    </header>
    <main>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>{{ trans('messages.payments.invoice_number') }}</th>
                    <th>{{ trans('messages.payments.invoice_date') }}</th>
                    <th>{{ trans('messages.payments.invoice_amount') }}</th>
                    <th>{{ trans('messages.payments.payment_amount') }}</th>
                    <th>{{ trans('messages.payments.amount_due') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr style="page-break-inside: avoid;">
                    <td class="align-center">{{ $payment->invoice->invoice_number }}</td>
                    <td class="align-center">{{ $payment->invoice->invoice_date->format("dS M Y") }}</td>
                    <td class="align-center">{!! htmlentities($currency->symbol) !!} {{ number_format($payment->invoice->total_amount,2) }}</td>
                    <td class="align-center">{!! htmlentities($currency->symbol) !!} {{ number_format($payment->amount,2) }}</td>
                    <td class="align-center">{!! htmlentities($currency->symbol) !!} {{ number_format($payment->invoice->total_due_amount,2) }}</td>
                </tr>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    </main>
</body>

</html>
