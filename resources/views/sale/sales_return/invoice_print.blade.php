<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Favicon-->
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
</head>
<body>
<header id="pageHeader" style="margin-bottom: 10px">

</header>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2 style="margin-bottom: 0px;">Megha Footwear</h2>
            <h6 style="margin-bottom: 0px;">Megha trading, 174, Siddik bazar Dhaka - 1000</h6>
            <h6 style="margin-bottom: 0px;">Hotline: 01841509263 Phone: 02226638333, 01720009263</h6>
            <h6 style="margin-bottom: 0px;">Bin No: 001067154-0205</h6>
            <h5 style="margin-bottom: 0px;margin-top: 0px;padding: 0">Product Wise Sale @if(request()->get('start')), Date:
                {{ date('d-m-Y',strtotime(request()->get('start'))) }} to {{ date('d-m-Y',strtotime(request()->get('end'))) }} @endif</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <table class="table table-bordered">
                <tr>
                    <th>Order Type</th>
                    <td>Return Product Order</td>
                </tr>
                <tr>
                    <th>Order No.</th>
                    <td>{{ $order->order_no }}</td>
                </tr>
                <tr>
                    <th>Order Date</th>
                    <td>{{ $order->date }}</td>
                </tr>
            </table>
        </div>

        <div class="col-xs-6">
            <table class="table table-bordered">
                <tr>
                    <th colspan="2" class="text-center">Customer Info</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $order->customer->name }}</td>
                </tr>
                <tr>
                    <th>Mobile</th>
                    <td>{{ $order->customer->mobile_no }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $order->customer->address }}</td>
                </tr>
            </table>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
{{--                    <th>Code</th>--}}
                    <th>Model</th>
                    <th>Size</th>
                    <th>Warehouse</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
                </thead>

                @php
                    $totalQuantity = 0;
                @endphp

                <tbody>
                @foreach($order->logs as $log)
                    @php
                        $totalQuantity += $log->quantity;
                    @endphp
                    <tr>
{{--                        <td>{{ $log->serial }}</td>--}}
                        <td>{{ $log->productItem->name??'' }}</td>
                        <td>{{ $log->productCategory->name??'' }}</td>
                        <td>{{ $log->warehouse->name??'' }}</td>
                        <td>{{ $log->quantity }}</td>
                        <td>Tk {{ number_format($log->selling_price, 2) }}</td>
                        <td>Tk {{ number_format($log->quantity * $log->selling_price, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Total Quantity</th>
                    <td>{{ number_format($totalQuantity, 2) }}</td>
                    <th class="text-right">Total Amount</th>
                    <td>Tk {{ number_format($order->total, 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
