<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ trans('messages.estimates.title') }}</title>
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
        /*font-family: "DejaVu Sans Mono", monospace !important;*/
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

    table .desc {
        text-align: left;
    }

    table .unit {
        background: #f3f3f3;
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
        width: 100px;
        color: #FFFFFF;
        float: right;
        text-align: center;
        display: inline-block;*/
        font-weight: bolder;
        font-size: 18px !important;
    }

    .status.draft {
        color: #e46a76;
    }

    .status.sent {
        color: #1cbcd8;
    }

    .status.expired {
        color: #ffb136;
    }

    .status.declined {
        color: #e74a25;
    }

    .status.accepted {
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

    .f-12{
        font-size: 12px !important;
    }

    .logo img {
        max-width: 150px;
    }

    .currency_symbol{
        font-family: "DejaVu Sans Mono", monospace !important;
    }
    
    </style>
</head>

<body>
    <header class="clearfix">
        <table cellpadding="0" cellspacing="0" class="billing">
            <tr>
                <td class="align-left logo">
                    @if(!is_null($estimateSetting->estimate_logo) && file_exists(public_path('uploads/estimate_logo/'.$estimateSetting->estimate_logo)))
                        <img src="{{ asset('uploads/estimate_logo/'.$estimateSetting->estimate_logo) }}" alt="Logo" />
                    @else
                        <img src="{{ asset('img/favicon/vipspm-dark-logo.png') }}" alt="Logo" />
                    @endif
                </td>
                <td>
                    <b>{{ strtoupper(trans('messages.estimates.title')) }}</b><br>
                    <b># {{ $estimate->estimate_number }}</b><br>
                    <div class="status {{ $estimate->status }}">
                        @if ($estimate->status == 'draft')
                            {{ strtoupper(trans('messages.estimates.status.draft')) }}
                        @elseif ($estimate->status == 'sent')
                            {{ strtoupper(trans('messages.estimates.status.sent')) }}
                        @elseif ($estimate->status == 'expired')
                            {{ strtoupper(trans('messages.estimates.status.expired')) }}
                        @elseif ($estimate->status == 'declined')
                            {{ strtoupper(trans('messages.estimates.status.declined')) }}
                        @elseif ($estimate->status == 'accepted')
                            {{ strtoupper(trans('messages.estimates.status.accepted')) }}
                        @else
                            {{ strtoupper(str_replace('_', ' ', $estimate->status)) }}
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-left">
                    <p>{{ ucfirst(trans('messages.estimates.to')) }}</p>
                    <p><b>{{ strtoupper($estimate->client->full_name) }}</b></p>
                    <p class="cpy-info">
                        @if($estimate->client->address)
                            {!! nl2br($estimate->client->address) !!}
                        @endif
                        @if($estimate->client->phone)
                            <br>{{ $estimate->client->phone }}
                        @endif
                        @if($estimate->client->mobile)
                            <br>{{ $estimate->client->mobile }}
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
                    <td class="align-center"><b>{{ trans('messages.estimates.estimate_date') }}: </b>{{ $estimate->estimate_date->format($setting->php_date_format) }}</td>
                    <td class="align-center"><b>{{ trans('messages.estimates.expiry_date') }}: </b>{{ $estimate->valid_till->format($setting->php_date_format) }}</td>
                    @if($estimate->reference)
                        <td class="align-center"><b>{{ trans('messages.estimates.reference') }} # </b>{{ $estimate->reference }}</td>
                    @endif
                </tr>
            </thead>
        </table>
    </header>
    <main>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="w-5 no">#</th>
                    <th class="w-40">{{ trans('messages.estimates.item_name') }}</th>
                    <th class="w-5 qty">{{ trans('messages.estimates.quantity') }}</th>
                    <th class="w-15">{{ trans('messages.estimates.unit_price') }}</th>
                    <th class="w-15">{{ trans('messages.estimates.tax') }}</th>
                    <th class="w-20 unit">{{ trans('messages.estimates.total_item_amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $count = 0; 
                    $taxes = [];
                @endphp
                @foreach($estimate->items as $item)
                    <tr style="page-break-inside: avoid;">
                        <td class="w-5 no">{{ ++$count }}</td>
                        <td class="w-40 desc">
                            <div class="item">{{ ucfirst($item->item_name) }}</div>
                            <div class="f-12">{{ $item->item_description }}</div>
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
                    <td class="w-15 subtotal">{{ trans('messages.estimates.sub_total') }}</td>
                    <td class="w-20 unit">{{ number_format($estimate->sub_total, 2) }}</td>
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
                @if($estimate->total_discount)
                <tr style="page-break-inside: avoid;" class="discount">
                    <td class="w-5 no">&nbsp;</td>
                    <td class="w-40 desc">&nbsp;</td>
                    <td class="w-5 qty">&nbsp;</td>
                    <td class="w-15">&nbsp;</td>
                    <td class="w-15 discount">{{ trans('messages.estimates.discount') }}</td>
                    <td class="w-20 unit">-{{ number_format($estimate->total_discount, 2) }}</td>
                </tr>
                @endif
                @if($estimate->adjustment)
                <tr style="page-break-inside: avoid;" class="discount">
                    <td class="w-5 no">&nbsp;</td>
                    <td class="w-40 desc">&nbsp;</td>
                    <td class="w-5 qty">&nbsp;</td>
                    <td class="w-15">&nbsp;</td>
                    <td class="w-15">{{ trans('messages.estimates.adjustment') }}</td>
                    <td class="w-20 unit">{{ number_format($estimate->adjustment, 2) }}</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr dontbreak="true" class="total">
                    <td colspan="5">{{ trans('messages.estimates.total') }}</td>
                    <td>{!! htmlentities($currency->symbol) !!} {{ number_format($estimate->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
        @if($estimateSetting->client_note)
        <div class="notices"><b>{{ trans('messages.estimates.note') }}: </b><br><span>{{ $estimateSetting->client_note }}</span></div>
        @endif
        @if($estimateSetting->terms_conditions)
        <div class="notices"><b>{{ trans('messages.estimates.terms_conditions') }}: </b><br><span>{{ $estimateSetting->terms_conditions }}</span></div>
        @endif
    </main>
</body>

</html>