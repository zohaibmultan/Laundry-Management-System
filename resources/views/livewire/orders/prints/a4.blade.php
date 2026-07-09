<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $lang->data['print_invoice'] ?? 'Print Invoice' }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @vite('resources/css/app.css')
</head>

<body onload="">
    <div class="card h-100 p-0 radius-12 tw-w-full">
        <div
            class="card-header tw-flex tw-items-center tw-justify-between border-bottom bg-base px-24 d-flex  flex-wrap gap-3 justify-content-between ">
            <div class="tw-flex tw-flex-col  tw-text-sm">
                <div class="text-lg tw-font-medium text-primary-light">
                    {{ $sitename }}
                </div>
                <div class="tw-flex tw-flex-col tw-mt-2">
                    <div class="">{{ $phone ? getCountryCode() : '' }}{{ (int) $phone }}</div>
                    <div class="">{{ $store_email }}</div>
                    <div class="">{{ $address }} - {{ $zipcode }}</div>
                    <div class="tw-mt-2">{{ $lang->data['tax'] ?? 'TAX' }}: {{ $tax_number }}</div>
                </div>
            </div>
            <div class="tw-flex tw-flex-col  tw-text-sm tw-items-end">
                <div class="text-lg tw-font-medium text-primary-light">
                </div>
                <div class="tw-flex tw-flex-col tw-mt-2 tw-items-end">
                    <div class="text-neutral-600">
                        {{ $lang->data['order_id'] ?? 'Order ID' }} : <span
                            class="tw-font-medium text-primary-light">#{{ $order->order_number }}</span>
                    </div>
                    <div class="text-neutral-600">
                        {{ $lang->data['order_date'] ?? 'Order Date' }} : <span
                            class="tw-font-medium text-primary-light">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="text-neutral-600">
                        {{ $lang->data['delivery_date'] ?? 'Delivery Date' }} : <span
                            class="tw-font-medium text-primary-light">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</span>
                    </div>

                    <div class="tw-mt-2 tw-flex tw-items-center tw-gap-2">
                        <div class="">
                            {{ $lang->data['order_status'] ?? 'Order Status' }} :
                        </div>
                        <div class="dropdown">
                            <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ getOrderStatus($order->status) }} </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-24  w-100 tw-w-full">
            <div class="table-responsive scroll-sm  w-100">
                <table class="table bordered-table sm-table mb-0 tw-w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="">#</th>
                            <th scope="col" class="">
                                {{ $lang->data['service_name'] ?? 'Service Name' }}</th>
                            <th scope="col" class=""> {{ $lang->data['color'] ?? 'Color' }}</th>
                            <th scope="col" class="">{{ $lang->data['rate'] ?? 'Rate' }}</th>
                            <th scope="col" class=""> {{ $lang->data['qty'] ?? 'QTY' }}</th>
                            <th scope="col" class=""> {{ $lang->data['total'] ?? 'Total' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderdetails as $item)
                            @php
                                $service = \App\Models\Service::where('id', $item->service_id)->first();
                            @endphp
                            <tr class="tw-text-sm">
                                <td>
                                    {{ $loop->index + 1 }}
                                </td>
                                <td class="">
                                    <div class="tw-flex tw-gap-4">
                                        <div class="tw-size-10">
                                            <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}"
                                                class="tw-object-contain" alt="">
                                        </div>
                                        <div class="tw-flex tw-flex-col">
                                            <p>{{ $service->service_name }}</p>
                                            <p class="text-info">[{{ $item->service_name }}]</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-primary">
                                    @if ($item->color_code != '')
                                        <div class="tw-size-6 tw-rounded-lg"
                                            style="background-color: {{ $item->color_code }}">
                                        </div>
                                    @else
                                        <div class="tw-size-6 tw-rounded-lg tw-bg-white">
                                        </div>
                                    @endif
                                </td>
                                <td class="text-primary">
                                    {{ getFormattedCurrency($item->service_price) }}
                                </td>
                                <td>
                                    {{ $item->service_quantity }}
                                </td>
                                <td class="text-primary">
                                    {{ getFormattedCurrency($item->service_detail_total) }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tw-flex tw-flex-col">
                @if (count($orderaddons) > 0)
                    <div class="col-4 mb-4  tw-mt-6">
                        <div class="">{{ $lang->data['addons'] ?? 'Addons' }}:</div>
                        @foreach ($orderaddons as $item)
                            <p class="text-sm mb-1">{{ $item->addon_name ?? '' }} : <b>
                                    {{ getFormattedCurrency($item->addon_price) }} </b></p>
                        @endforeach
                    </div>
                @endif
                <div class="tw-flex tw-justify-between tw-items-start @if (count($orderaddons) <= 0) tw-mt-6 @endif">
                    <div class="tw-flex tw-flex-col ">
                        <div class="">{{ $lang->data['invoice_to'] ?? 'Invoice To' }}</div>
                        <div class="tw-mt-2 tw-font-medium tw-text-sm">
                            {{ $customer->name ?? 'Walk-In Customer' }}
                        </div>
                        <div class="tw-text-sm">
                            {{ $customer && $customer->phone ? getCountryCode() : '' }}
                            {{ $customer && $customer->phone ? (int) $customer->phone : 'Phone' }}
                        </div>
                        <div class=" tw-text-sm">
                            {{ $customer->email ?? 'Email' }}
                        </div>
                        <div class=" tw-text-sm">
                            {{ $customer->address ?? '' }}
                        </div>

                        <div class="tw-text-sm tw-mt-2">
                            {{ $lang->data['vat'] ?? 'VAT' }} : {{ $customer->tax_number ?? 'TAX' }}
                        </div>
                    </div>

                    <div class="tw-flex tw-flex-col ">
                        <div class="pb-2">{{ $lang->data['payment_details'] ?? 'Payment Details' }}</div>
                        <div class="tw-flex tw-justify-between tw-items-center tw-w-[17rem] tw-mt-2">
                            <div class="  tw-text-sm">
                                {{ $lang->data['sub_total'] ?? 'Sub Total' }}
                            </div>
                            <div class=" tw-text-sm">
                                {{ getFormattedCurrency($order->sub_total) }}
                            </div>
                        </div>
                        <div class="tw-flex tw-justify-between tw-items-center tw-w-[17rem]">
                            <div class="  tw-text-sm">
                                {{ $lang->data['addon'] ?? 'Addon' }}
                            </div>
                            <div class=" tw-text-sm">
                                {{ getFormattedCurrency($order->addon_total) }}
                            </div>
                        </div>
                        <div class="tw-flex tw-justify-between tw-items-center tw-w-[17rem]">
                            <div class="  tw-text-sm">
                                {{ $lang->data['discount'] ?? 'Discount' }}
                            </div>
                            <div class=" tw-text-sm">
                                {{ getFormattedCurrency($order->discount) }}
                            </div>
                        </div>
                        <div class="tw-flex tw-justify-between tw-items-center tw-w-[17rem]">
                            <div class="  tw-text-sm">
                                {{ $lang->data['tax'] ?? 'Tax' }}
                                ({{ $order->tax_percentage }}%)
                            </div>
                            <div class=" tw-text-sm">
                                {{ getFormattedCurrency($order->tax_amount) }}
                            </div>
                        </div>
                        <div class="tw-flex tw-justify-between tw-items-center tw-w-[17rem] tw-mt-2  ">
                            <div class=" tw-font-bold tw-text-sm">
                                {{ $lang->data['gross_total'] ?? 'Gross Total' }}
                            </div>
                            <div class="tw-font-bold tw-text-sm">
                                {{ getFormattedCurrency($order->total) }}
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="tw-mt-4">
                <div class="tw-flex tw-justify-between tw-text-sm tw-mt-4 ">
                    <div class=""><span class="tw-font-medium">{{ $lang->data['notes'] ?? 'Notes' }}
                            :</span> {{ $order->note }}</div>
                </div>
                <div class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-mt-4">
                    <div class="tw-w-full tw-h-[1px]  tw-from-transparent tw-to-neutral-300 tw-bg-gradient-to-r">
                    </div>
                    <div class="tw-shrink-0 tw-font-light">Powered By <span
                            class="tw-font-bold">{{ getApplicationName() }}</span> </div>
                    <div class="tw-w-full tw-h-[1px] tw-from-transparent tw-to-neutral-300 tw-bg-gradient-to-l">
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
