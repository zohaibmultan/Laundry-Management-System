<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Print Tag - #{{ $order->order_number }}</title>
        <link href="https://fonts.googleapis.com/css?family=Calibri:400,700,400italic,700italic">
        <style>
            @page {
                size: 50mm 30mm;
                margin: 0mm;
            }

            body {
                margin: 0;
                padding: 4px;
                font-family: Calibri, sans-serif;
                text-align: center;
                size: 50mm 30mm;
            }

            .tag-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
                box-sizing: border-box;
            }

            .order-number {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 2px;
            }

            .order-date {
                font-size: 12px;
                color: #555;
            }
        </style>
    </head>

    <body>
        <div class="tag-container">
            <div class="order-number">
                {{ $lang->data['order_no'] ?? 'Order No' }}: #{{ $order->order_number }}
            </div>
            <div class="order-date">
                {{ $lang->data['order_date'] ?? 'Order Date' }}: {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}
            </div>
        </div>
    </body>

    </html>
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
