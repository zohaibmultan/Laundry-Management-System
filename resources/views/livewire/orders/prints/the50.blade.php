<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $lang->data['print_invoice'] ?? 'Print Invoice' }}</title>
    <link href="https://fonts.googleapis.com/css?family=Calibri:400,700,400italic,700italic">
    <style>
        * {
            font-weight: normal !important;
        }

        @page {
            size: 50mm auto;
            margin: 0mm 0 0mm 0;
        }

        body {
            size: 50mm auto;
            margin: 0px;
            font-family: Calibri;
        }

        @media screen {

            .header,
            .footer {
                display: none;
            }
        }
    </style>
    <style>
        .mb-0 {
            margin-bottom: 0;
        }

        .my-50 {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .my-0 {
            margin-top: 0;
            margin-bottom: 0;
        }

        .my-5 {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-15 {
            margin-bottom: 15px;
        }

        .mr-18 {
            margin-right: 18px;
        }

        .mr-25 {
            margin-right: 25px;
        }

        .mb-25 {
            margin-bottom: 25px;
        }

        .h4,
        .h5,
        .h6,
        h4,
        h5,
        h6 {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .login-wrapper {
            background-size: 100% 100%;
            height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-wrapper:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: block;
            background: rgba(0, 0, 0, 0.5);
        }

        .login_box {
            text-align: center;
            position: relative;
            max-width: 80mm;
            background: #343434;
            padding: 10px 10px;
            border-radius: 10px;
        }

        .text-black {
            color: #000000 !important;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            -webkit-print-color-adjust: exact;
            margin: 15px 0;
            font-size: 16px !important;
            font-weight: bold !important;
            border-bottom: 1px dashed #858080;
            border-top: 1px dashed #858080;
        }

        .login_box .form-control {
            height: 60px;
            margin-bottom: 25px;
            padding: 12px 25px;
        }

        .btn-login {
            color: #fff;
            background-color: #45C203;
            border-color: #45C203;
            width: 100%;
            line-height: 45px;
            font-size: 17px;
        }

        .btn-login:hover,
        .btn-login:focus {
            color: #fff;
            background-color: transparent;
            border-color: #fff;
        }

        .invoice-card {
            display: flex;
            flex-direction: column;
            width: 50mm;
            background-color: #fff;
            border-radius: 5px;
            margin: 15px auto;
        }

        .invoice-head,
        .invoice-card .invoice-title {
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-title {
            background-color: #000000 !important;
            color: #ffffff !important;
            padding: 10px;
            -webkit-print-color-adjust: exact;
        }

        .invoice-head {
            flex-direction: column;
            margin-bottom: 0px;
        }

        .invoice-card .invoice-title {
            margin: 15px 0;
        }

        .invoice-details {
            border-top: 0.5px dashed #747272;
            border-bottom: 0.5px dashed #747272;
        }

        .invoice-list {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px dashed #858080;
        }

        .invoice-list .row-data {
            border-bottom: 1px dashed #858080;
        }

        .invoice-list .row-data:last-child {
            border-bottom: 0;
            margin-bottom: 0;
        }

        .invoice-list .heading {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .invoice-list .heading1 {
            font-size: 14px;
            font-weight: 500;
            margin: 0;
        }

        .invoice-list thead tr td {
            font-size: 15px;
            font-weight: 600;
            padding: 5px 0;
        }

        .invoice-list tbody tr td {
            line-height: 25px;
        }

        .row-data {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            width: 100%;
        }

        .col {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: space-between;
            width: 100%;
        }

        .middle-data {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-info {
            max-width: 200px;
        }

        .item-title {
            font-size: 14px;
            margin: 0;
            line-height: 19px;
            font-weight: 500;
        }

        .item-size {
            line-height: 19px;
        }

        .item-size,
        .item-number {
            margin: 5px 0;
        }

        .invoice-footer {
            margin-top: 20px;
        }

        .gap_right {
            border-right: 1px solid #ddd;
            padding-right: 15px;
            margin-right: 15px;
        }

        .b_top {
            border-top: 1px solid #ddd;
            padding-top: 12px;
        }

        .food_item {
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-top: 5px solid #1DB20B;
            padding: 15px;
            margin-bottom: 25px;
            transition-duration: 0.4s;
        }

        .bhojon_title {
            margin-top: 6px;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .food_item .img_wrapper {
            padding: 15px 5px;
            background-color: #ececec;
            border-radius: 6px;
            position: relative;
            transition-duration: 0.4s;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .food_item .table_info {
            font-size: 11px;
            background: #1db20b;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 4px 8px;
            color: #fff;
            border-radius: 15px;
            text-align: center;
        }

        .food_item:focus,
        .food_item:hover {
            background-color: #383838;
        }

        .food_item:focus .bhojon_title,
        .food_item:hover .bhojon_title {
            color: #fff;
        }

        .food_item:hover .img_wrapper,
        .food_item:focus .img_wrapper {
            background-color: #383838;
        }

        .btn-4 {
            border-radius: 0;
            padding: 15px 22px;
            font-size: 16px;
            font-weight: 500;
            color: #fff;
            min-width: 130px;
        }

        .btn-4.btn-green {
            background-color: #1DB20B;
        }

        .btn-4.btn-green:focus,
        .btn-4.btn-green:hover {
            background-color: #3aa02d;
            color: #fff;
        }

        .btn-4.btn-blue {
            background-color: #115fc9;
        }

        .btn-4.btn-blue:focus,
        .btn-4.btn-blue:hover {
            background-color: #305992;
            color: #fff;
        }

        .btn-4.btn-sky {
            background-color: #1ba392;
        }

        .btn-4.btn-sky:focus,
        .btn-4.btn-sky:hover {
            background-color: #0dceb6;
            color: #fff;
        }

        .btn-4.btn-paste {
            background-color: #0b6240;
        }

        .btn-4.btn-paste:hover,
        .btn-4.btn-paste:focus {
            background-color: #209c6c;
            color: #fff;
        }

        .btn-4.btn-red {
            background-color: #eb0202;
        }

        .btn-4.btn-red:focus,
        .btn-4.btn-red:hover {
            background-color: #ff3b3b;
            color: #fff;
        }

        .text-center {
            text-align: center;
        }

        .border-top {
            border-top: 2px dashed #858080;
            background: #ececec;
        }

        .text-bold {
            font-weight: bold !important;
        }

        .text-sm {
            font-size: 0.9rem !important;
        }
    </style>
</head>

<body>
    <div class="page-wrapper" style="padding:5px">
        <div class="invoice-card">
            <div class="invoice-head  ">
                <img src="{{ getSiteLogo() }}" style="height:34px;max-width:80%;" alt="">
                <h4 style="text-align: center " class="text-sm">{{ $sitename }}</h4>
                <p class="my-0 text-sm">{{ $address }} - {{ $zipcode }}</p>
                <p class="my-0 text-sm">{{ $phone ? getCountryCode() : '' }}{{ $phone }}</p>
                <p class="my-0 text-sm">{{ $store_email }}</p>
                <p class="my-0 text-sm">{{ $tax_number }}</p>
            </div>
            <div class="invoice-details" style="border-top:none;margin-top : 0;">
                <div class="invoice-list">
                    <div class="text-center" style="margin-top:8px;margin-bottom:8px;">
                        <h4 class="heading font-bold" style="font-size: 25px">
                            {{ $lang->data['tax_invoice'] ?? 'Tax Invoice' }}</h4>
                        <h4 class="heading heading-child"></h4>
                    </div>
                    <div class="row" style="border:none; margin-bottom: 0px;padding : 0px;">
                        <div class="item-info">
                            <div class="item-title text-sm">{{ $lang->data['invoice_to'] ?? 'Invoice To' }}:
                            </div>
                        </div>
                        <h5 class="my-5 ">
                            {{ $customer->name ?? ($lang->data['walk_in_customer'] ?? 'Walk-In Customer') }}<br />
                            @if ($customer && $customer->phone)
                                {{ getCountryCode() }} {{ $customer->phone }}<br />
                            @endif
                            @if ($customer && $customer->email)
                                {{ $customer->email }}<br />
                            @endif
                            @if ($customer && $customer->address)
                                {{ $customer->address }}<br />
                            @endif
                            @if ($customer && $customer->tax_number)
                                {{ $lang->data['vat'] ?? 'VAT' }}: {{ $customer->tax_number }}
                            @endif
                        </h5>
                    </div>
                    <div class="row" style="border:none; margin-bottom: 1px">
                        <div class="item-info">
                            <h5 class="item-title">{{ $lang->data['order_no'] ?? 'Order No' }}:</h5>
                        </div>
                        <h5 class="my-5"><b>{{ $order->order_number }}</b></h5>
                    </div>
                    <div class="row" style="border:none;">
                        <div class="item-info">
                            <h5 class="item-title">{{ $lang->data['order_date'] ?? 'Order Date' }}:</h5>
                        </div>
                        <h5 class="my-5">
                            <b>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</b>
                        </h5>
                    </div>
                    <div class="row" style="border:none;">
                        <div class="item-info">
                            <h5 class="item-title">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:
                            </h5>
                        </div>
                        <h5 class="my-5">
                            <b>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</b>
                        </h5>
                    </div>
                    <div class="text-black"
                        style="text-align: right; margin : 4px; margin-left: 0px; margin-right: 0px;">
                        <h6 class="heading1" style="font-weight:bold; font-size:14px;">
                            {{ $lang->data['name'] ?? 'Name' }}</h6>
                        <h6 class="heading1 heading-child" style="font-weight:bold; font-size:14px;">
                            {{ $lang->data['qty'] ?? 'QTY' }}</h6>
                        <h6 class="heading1 heading-child" style="font-weight:bold; font-size:14px;">
                            {{ $lang->data['total'] ?? 'Total' }}</h6>
                    </div>
                    @php
                        $qty = 0;
                    @endphp
                    @foreach ($orderdetails as $item)
                        @php
                            $service = \App\Models\Service::where('id', $item->service_id)->first();
                        @endphp
                        <div class="row-data"
                            style="text-align: center;margin-top: 5px; padding-bottom: 8px; align-items: center">
                            <div class="item-info" style="width: 82px;text-align: initial;">

                                <h5 class="item-title"><b>{{ $service->service_name }}</b></h5>
                                <h5 class="item-title"><b>[{{ $item->service_name }}]</b></h5>
                            </div>
                            <h5 class="my-5"><b>{{ $item->service_quantity }}</b></h5>
                            <h5 class="my-5"><b>{{ $item->service_detail_total }}</b>
                            </h5>
                        </div>
                    @endforeach
                    @php
                        $addons = \App\Models\OrderAddonDetail::where('order_id', $order->id)->get();
                    @endphp
                    @if ($addons)
                        @if (count($addons) > 0)
                            <h4 style="padding-top: 5px;">{{ $lang->data['addons'] ?? 'Addons' }}</h4>
                            @foreach ($addons as $row)
                                <div class="row-data" style="text-align: center;margin-top: 5px; padding-bottom: 8px;">
                                    <h5 class="my-5" style="   text-align: initial; width: 82px;">
                                        <b>{{ $row->addon_name }}</b>
                                    </h5>
                                    <h5 class="my-5 "><b>-</b></h5>
                                    <h5 class="my-5"><b>-</b></h5>
                                    <h5 class="my-5"><b>{{ $row->addon_price }}</b>
                                    </h5>
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
                <div class="invoice-footer mb-15">

                    <div class="row-data">
                        <div class="item-info">
                            <h5 class="item-title">{{ $lang->data['sub_total'] ?? 'Sub Total' }}:</h5>
                        </div>
                        <h5 class="my-0">{{ $order->sub_total }}</h5>
                    </div>
                    <div class="row-data">
                        <div class="item-info">
                            <h5 class="item-title">{{ $lang->data['addon'] ?? 'Addon' }}:</h5>
                        </div>
                        <h5 class="my-0">
                            {{ $order->addon_total }}
                        </h5>
                    </div>
                    <div class="row-data">
                        <div class="item-info">
                            <h5 class="item-title">{{ $lang->data['discount'] ?? 'Discount' }}:</h5>
                        </div>
                        <h5 class="my-0">{{ $order->discount }}</h5>
                    </div>
                    @if ($order->tax_type == 2)
                        <div class="row-data">
                            <div class="item-info">
                                <h5 class="item-title">{{ $lang->data['before_tax'] ?? 'Before Tax' }}
                                    :</h5>
                            </div>
                            <h5 class="my-0">{{ $order->sub_total }}</h5>
                        </div>
                    @endif
                    <div class="row-data">
                        <div class="item-info">
                            <h5 class="item-title">{{ $lang->data['tax'] ?? 'Tax' }}
                                ({{ $order->tax_percentage }}%):</h5>
                        </div>
                        <h5 class="my-0">{{ $order->tax_amount }}</h5>
                    </div>
                    <div class="row-data">
                        <div class="item-info">
                            <h5 class="item-title">{{ $lang->data['gross_total'] ?? 'Gross Total' }}:
                            </h5>
                        </div>
                        <h5 class="my-0">{{ $order->total }}
                        </h5>
                    </div>
                    <div class="row-data">
                        <div class="item-info">
                            @php
                                $current_paid_amount = \App\Models\Payment::where('order_id', $order->id)->sum(
                                    'received_amount',
                                );
                            @endphp
                            <h5 class="item-title">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}:
                            </h5>
                        </div>
                        <h5 class="my-0">{{ $current_paid_amount }}
                        </h5>
                    </div>
                    @php
                        $received_payment_row = \App\Models\Payment::where('order_id', $order->id)->sum(
                            'received_amount',
                        );
                        $received_amount = 0;
                        $inline_payment_balance = 0;
                        if ($received_payment_row) {
                            $received_amount = $received_payment_row;
                            $inline_payment_balance = $order->total - $received_amount;
                        }
                    @endphp
                    @if ($inline_payment_balance != 0)
                        <div class="row-data">
                            <div class="item-info">
                                <h5 class="item-title">
                                    {{ $lang->data['invoice_balance'] ?? 'Invoice Balance' }}:
                                </h5>
                            </div>
                            <h5 class="my-0">
                                {{ $order->total - $received_amount }}
                            </h5>
                        </div>
                    @endif
                    @if ($customer)
                        @php
                            $inline_invoice_amount = \App\Models\Order::where('customer_id', $customer->id)->sum(
                                'total',
                            );
                            $inline_payment = \App\Models\Payment::where('customer_id', $customer->id)->sum(
                                'received_amount',
                            );
                            $inline_balance = $inline_invoice_amount - $inline_payment;
                        @endphp
                        @if ($inline_balance != 0)
                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title">
                                        {{ $lang->data['customer_balance'] ?? 'Customer Balance' }}:
                                    </h5>
                                </div>
                                <h5 class="my-0">
                                    @if ($inline_balance < 0)
                                        {{ $inline_balance * -1 }} {{ 'Cr' }}
                                    @else
                                        {{ $inline_balance }} {{ 'Dr' }}
                                    @endif
                                </h5>
                            </div>
                        @endif
                    @endif
                    <hr>
                </div>
                <div class="invoice_address">
                    <div class="text-center">
                        @if (isset($site['default_thanks_message']))
                            <div class="text-sm">
                                {{ isset($site['default_thanks_message']) && !empty($site['default_thanks_message']) ? $site['default_thanks_message'] : '' }}
                            </div>
                        @endif
                        <p class=" text-sm">{{ $lang->data['powered_by'] ?? 'Powered By   ' }}
                            {{ getApplicationName() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        "use strict";
        window.onload = function() {
            window.print();
        }
        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>

</html>
