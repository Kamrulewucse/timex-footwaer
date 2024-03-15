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
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
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
        .header-design{

        }
        @media screen {
            /*div.divFooter {*/
            /*    display: none;*/
            /*}*/
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
<header id="pageHeader" style="margin-bottom: 10px;">
    <div class="row" style="">
        <div class="col-xs-2 text-center" style="padding: 9px 0px;background-color: #AC3B2B !important;padding-bottom: 11px;">
            <div style="">
                <h1><strong style="color: #fff;font-size: 60px;letter-spacing: 5px;padding: 5px 20px;color: #1C324A!important;background: #fff !important;border-radius: 30px 0px;">FTI</strong></h1>
            </div>
        </div>
        <div class="col-xs-8 text-center" style="padding: 25px 0px;background-color: #AC3B2B !important;">
            <h1 style="margin-top: 6px;margin-bottom: 0;"><strong style="color: #fff !important;font-size: 30px;letter-spacing: -2px;">FARUK TRADE INTERNATIONAL</strong></h1>
            <strong style="background-color: #fff !important;color: #1C324A !important;padding: 3px 5px;border-radius: 5px;">All Kinds of Footwear Importer & Wholesaler</strong>
        </div>
        <div class="col-xs-2 text-center">
            <div style="">
{{--                <strong style="font-size: 12px;color: #ce461d !important;">Gomti Footwear </strong>--}}
{{--                <p style="margin-bottom:0;font-size: 8px;">Ac No:135412200212794</p>--}}
{{--                <p style="font-size: 8px;">Uttara Bank LTD Dhaka</p>--}}
                <img width="70" height="50" src="{{ asset('img/faruk_one.png') }}">
            </div>
            <div style="margin-top: 6px;margin-bottom: 10px;">
{{--                <strong style="font-size: 12px;color: #ce461d !important;">Gomti Footwear </strong>--}}
{{--                <p style="margin-bottom:0;font-size: 8px;">Ac No:135412200212794</p>--}}
{{--                <p style="font-size: 8px;">Uttara Bank LTD Dhaka</p>--}}
                <img width="70" height="50" src="{{ asset('img/faruk_two.png') }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4" style="background-color: #cf800a !important;height: 40px;">
            <div>
                <p style="padding-top: 9px;margin-left: 10px;">
                    NO: {{ $order->order_no }}
                </p>
            </div>
        </div>
        <div class="col-xs-4 text-center" style="background-color: #AC3B2B !important;height: 40px;">
            <div>
                <strong style="color: #fff !important;padding-top: 9px;font-size: 25px;">INVOICE</strong>
            </div>
        </div>
        <div class="col-xs-4 text-center" style="background-color: #3F5263 !important;height: 40px;">
            <div>
                <p style="color: #fff !important;padding-top: 10px;">Date: {{ date('d-m-Y',strtotime($order->date)) }}</p>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="row" style="border: 1px solid black;margin-bottom: 5px;">
        <div class="col-xs-2 text-center" style="background-color: #AC3B2B !important;border: 3px solid black;height: 30px;">
            <div>
                <p style="color: white !important;padding-top: 3px;">Name: </p>
            </div>
        </div>
        <div class="col-xs-10" style="height: 30px;">
            <div>
                <p style="padding-top: 5px;font-weight: bold;">{{ $order->customer->name??'' }}</p>
            </div>
        </div>
    </div>
    <div class="row" style="border: 1px solid black;margin-bottom: 5px;">
        <div class="col-xs-2 text-center" style="background-color: #AC3B2B !important;height: 30px;border: 3px solid black;">
            <div>
                <p style="color: white !important;padding-top: 3px;">Address</p>
            </div>
        </div>
        <div class="col-xs-4" style="height: 30px;">
            <div>
                <p style="padding-top: 5px;font-weight: bold;">{{ $order->customer->address??'' }}</p>
            </div>
        </div>
        <div class="col-xs-2 text-center" style="background-color: #AC3B2B !important;height: 30px;border: 3px solid black;">
            <div>
                <p style="color: white !important;padding-top: 3px;">Cell</p>
            </div>
        </div>
        <div class="col-xs-4" style="height: 30px;">
            <div>
                <p style="padding-top: 5px;font-weight: bold;">{{ $order->customer->mobile_no??'' }}</p>
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
            <th class="text-center" style="border: 2px solid black !important;background-color: #AC3B2B !important;color: white !important;"> SL NO. </th>
            <th style="border: 2px solid black !important;background-color: #AC3B2B !important;color: white !important;"> Description </th>
            <th style="border: 2px solid black !important;background-color: #AC3B2B !important;color: white !important;"> Quantity </th>
            <th class="text-right" style="border: 2px solid black !important;background-color: #AC3B2B !important;color: white !important;">Unit Price</th>
            <th class="text-right" style="border: 2px solid black !important;background-color: #AC3B2B !important;color: white !important;">Amount TK.</th>
        </tr>
        </thead>
        <?php
        $subTotal = 0;
        $total = 0;
        $totalAmount = 0;
        ?>
{{--        <tbody>--}}
        @foreach($order->products as $key => $item)
            @php
                $totalQuantity += $item->quantity;
                 if(auth()->user()->role == 2){
                        $subTotal  += ($item->buy_price) * $item->quantity;
                        $total += (($item->buy_price) * $item->quantity) + $order->transport_cost + $order->vat - $order->discount - $order->return_amount;
                        $totalAmount = ((($item->buy_price) * $item->quantity) + $order->transport_cost + $order->vat - $order->discount - $order->return_amount) + $order->paid;

                 } else{
                       $subTotal = $order->sub_total;
                       $total = $order->total;
                       $totalAmount = $order->current_due + $order->paid;
                   }

            @endphp
            <tr>
                <td class="text-center" >{{ $key+1 }}</td>
                <td>
                    {{ $item->productItem->name??'' }} - {{ $item->productCategory->name??'' }}
                </td>
                <td>
                    {{ $item->quantity }}
                </td>
                <td class="text-right" width="100">
                    @if(auth()->user()->role == 2)
                        Tk  {{ number_format($item->buy_price, 2) }}
                    @else
                        Tk  {{ number_format($item->unit_price, 2) }}
                    @endif
                </td>
                <td class="text-right" width="100">
                    @if(auth()->user()->role == 2)
                        Tk  {{ number_format(($item->buy_price) * $item->quantity, 2) }}
                    @else
                        Tk  {{ number_format($item->total, 2) }}
                    @endif
                </td>
            </tr>
        @endforeach
{{--        </tbody>--}}
        <?php
            $rowspan = 3;
            if ($order->previous_due>0){$rowspan += 1;}
            if ($order->transport_cost>0){$rowspan += 1;}
            if ($order->discount>0){$rowspan += 1;}
        ?>
        <tr>
            <td rowspan="{{ $rowspan }}" style="text-align: left !important;" colspan="2">Note:</td>
            <td rowspan="{{ $rowspan }}" style="background-color: #AC3B2B;color: #fff;">{{ $totalQuantity }}</td>
            <td style="background-color: #AC3B2B;color: #fff;">Amount</td>
            <td>{{ number_format($total,2) }}</td>
        </tr>
        @if($order->previous_due>0)
            <tr>
                <td>Previous Due</td>
                <td>{{ number_format($order->previous_due,2) }}</td>
            </tr>
        @endif
        @if($order->transport_cost>0)
            <tr>
                <td>Transport Cost</td>
                <td>{{ number_format($order->transport_cost,2) }}</td>
            </tr>
        @endif
        @if($order->discount>0)
            <tr>
                <td>Discount</td>
                <td>{{ number_format($order->discount,2) }}</td>
            </tr>
        @endif
        <tr>
            <td style="background-color: #AC3B2B;color: #fff;">Deposit</td>
            <td>{{ number_format($order->paid,2) }}</td>
        </tr>
        <tr>
            <td style="background-color: #AC3B2B;color: #fff;">Due</td>
            <td>{{ number_format($order->current_due,2) }}</td>
        </tr>
    </table>
@endif

<div class="divFooter" style="width: 100%">
    <div class="row" style="">
        <div class="col-xs-9" style="height: 20px;">
            <div class="text-left" style="clear: both;">
                <p>Received by: </p>
            </div>
        </div>
        <div class="col-xs-3 text-right">
            <span style="border-top: 1px solid black">Authorised Signature</span>
        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6 text-center" style="background-color: #AC3B2B !important;padding: 10px 0px;font-size: 11px;color: #fff !important;">
              Shop # 32 (2nd Floor), Fulbaria Super Market-1, Dhaka-1000
        </div>
        <div class="col-xs-6 text-center" style="background-color: #0c1e48 !important;padding: 10px 0px;color: #fff !important;font-size: 10px;">
            Cell: 01794-909001,01682-691852,01715-009429, Tel: +88-02-226637469
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            Software developed by Tech&Byte. Mobile: +8801521499793,+8801603278404
        </div>
    </div>
</div>


<script>
    // window.print();
    // window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
