<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Favicon-->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">

    <style>
        @page {
            @top-center {
                content: element(pageHeader);
            }
        }
        #pageHeader{
            position: running(pageHeader);
        }

        table.table-bordered{
            border:1px solid black !important;
            margin-top:20px;
        }
        table.table-bordered th{
            border:1px solid black !important;
        }
        table.table-bordered td{
            border:1px solid black !important;
        }

        .product-table th, .table-summary th {
            padding: 2px !important;
            text-align: center !important;
        }

        .product-table td, .table-summary td {
            padding: 2px !important;
            text-align: center !important;
        }

        @media screen {
            div.divFooter {
                display: none;
            }
        }
        @media print {
            div.divFooter {
                position: fixed;
                bottom: 0;
            }
        }
    </style>
</head>
<body>
<header id="pageHeader" style="margin-bottom: 10px">
    <div class="row">
        <div class="col-xs-12">
            @if ($order->company_branch_id == 2)
                <img src="{{ asset('img/your_choice_plus.png') }}"style="margin-top: 10px; float:inherit">
            @else
                <img src="{{ asset('img/your_choice.png') }}"style="margin-top: 10px; float:inherit">
            @endif
            <br>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 text-center" style="border: 1px solid black; padding: 3px; border-radius: 7px">
            <strong> Challan </strong>
        </div>
    </div>
    <br>
    <div class="row" style="border: 1px solid black; margin-top: 3px; font-size: 12px">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-6">
                    <strong>Name: </strong>{{ $order->customer->name??'' }} <br>
                    <strong>Address: </strong>{{$order->customer->address??'' }} <br>
                    <strong>Mobile No. : </strong>{{$order->customer->mobile_no??'' }} <br>
                    <strong> Received by : </strong>{{ $order->received_by }}
                </div>

                <div class="col-xs-6 text-right">
                    <strong>ID : </strong>{{ $order->customer->id }} <br>
                    <strong>Invoice No : </strong>{{ $order->order_no }} <br>
                    <strong>Date : </strong>{{ $order->date->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $totalQuantity = 0;
@endphp

@if(count($order->products) > 0)
    <br>
    <table class="table table-bordered product-table pt-4" style="margin-top: 5px; margin-bottom: 1px !important; font-size: 12px">
        <thead>
            <tr>
{{--                <th class="text-center" > Code </th>--}}
                <th> Model </th>
                <th> Size </th>
                <th> Warehouse </th>
                <th> Quantity </th>
            </tr>
        </thead>

        <tbody>
            @foreach($order->products as $key => $item)
                @php
                    $totalQuantity += $item->quantity
                @endphp
                <tr>
{{--                    <td class="text-center" >{{ $item->serial }}</td>--}}
                    <td>
                        {{ $item->productItem->name??'' }}
                    </td>
                    <td>
                        {{ $item->productCategory->name??'' }}
                    </td>
                    <td>
                        {{ $item->warehouse->name??'' }}
                    </td>
                    <td>
                        {{ $item->quantity }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th class="text-right" colspan="3">Total Quantity</th>
                <td class="text-left" >{{$totalQuantity}}</td>
            </tr>
        </tbody>
    </table>
@endif

{{--<div class="divFooter" style="width: 100%">--}}
{{--    <div class="row">--}}
{{--        <div class="col-xs-6">--}}
{{--            <span style="border-top: 1px solid black">Received With Good Condition By</span>--}}
{{--        </div>--}}

{{--        <div class="col-xs-6 text-right">--}}
{{--            <span style="border-top: 1px solid black">Authorised Signature</span>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="row">--}}
{{--        <div class="col-xs-12 text-center"> <br>--}}
{{--            Software developed by Tech&Byte. Mobile: 01884697775--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <br>--}}
{{--</div>--}}


<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
