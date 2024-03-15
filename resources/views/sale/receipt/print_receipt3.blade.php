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
            .pagebreak { page-break-before: always; }
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
{{--    <div class="row" style="">--}}
{{--        <div class="col-xs-12 text-center" style="">--}}
{{--            <h1 style="margin-top: 6px;margin-bottom: 0;"><strong style="font-size: 30px;letter-spacing: -2px;">FARUK TRADE INTERNATIONAL</strong></h1>--}}
{{--            <strong style="color: #1C324A !important;">All Kinds of Footwear Importer & Wholesaler</strong><br/>--}}
{{--            <strong style="color: #1C324A !important;">Shop # 32 (2nd Floor), Fulbaria Super Market-1, Dhaka-1000</strong><br/>--}}
{{--            <strong style="color: #1C324A !important;">Cell: 01794-909001,01715-009429,01644-212715</strong>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <div class="col-xs-12 text-center" style="height: 40px;">
            <div>
                <strong style="padding-top: 9px;font-size: 25px;">INVOICE</strong>
            </div>
        </div>
    </div>
</header>
<table width="100%">
    <tr>
        <td width="20%">Bill NO</td>
        <td>:</td>
        <td>{{ $order->order_no }}</td>
        <td style="width: 50%;text-align: right;">Invoice Date: {{ date('d-m-Y',strtotime($order->date)) }}</td>
    </tr>
    <tr>
        <td>Customer Name</td>
        <td>:</td>
        <td>{{ $order->customer->name??'' }}</td>
        <td style="width: 50%;text-align: right;"></td>
    </tr>
    <tr>
        <td>Mobile No.</td>
        <td>:</td>
        <td>{{ $order->customer->mobile_no??'' }}</td>
        <td style="width: 50%;text-align: right;"></td>
    </tr>
    <tr>
        <td>Address</td>
        <td>:</td>
        <td>{{ $order->customer->address??'' }}</td>
        <td style="width: 50%;text-align: right;"></td>
    </tr>
</table>
@php
    $totalQuantity = 0;
@endphp

@if(count($order->products) > 0)
    <table class="table table-bordered product-table pt-4" style="margin-top: 10px; margin-bottom: 1px !important; font-size: 12px">
        <thead>
        <tr>
            <th class="text-center" style="border: 1px solid black !important;"> SL NO. </th>
            <th style="border: 1px solid black !important;"> Description </th>
            <th style="border: 1px solid black !important;"> Size </th>
            <th style="border: 1px solid black !important;"> Quantity </th>
            <th class="text-right" style="border: 1px solid black !important;">Unit Price</th>
            <th class="text-right" style="border: 1px solid black !important;">Amount TK.</th>
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
            <tr class="{{ $key==26?'pagebreak':'' }}">
                <td class="text-center" >{{ $key+1 }}</td>
                <td>
                    {{ $item->productItem->name??'' }}
                </td>
                <td>{{ $item->productCategory->name??'' }}</td>
                <td>
                    {{ $item->quantity }} Pair
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
        <tr>
            <td colspan="3" style="text-align: right !important;"><b>Total</b></td>
            <td>{{ $totalQuantity }} Pair</td>
            <td></td>
            <td>Tk  {{ number_format($total) }}</td>
        </tr>
        <?php
            $rowspan = 3;
            if ($order->previous_due>0){$rowspan += 1;}
            if ($order->transport_cost>0){$rowspan += 1;}
            if ($order->discount>0){$rowspan += 1;}
            if ($order->sale_adjustment>0){$rowspan += 1;}
            if ($order->return_amount>0){$rowspan += 1;}
        ?>
        <tr>
            <td rowspan="{{ $rowspan }}" style="text-align: left !important;border-left: 1px solid #fff !important;border-bottom: 1px solid #fff !important;border-right: 1px solid #fff !important;" colspan="3"></td>
            <td rowspan="{{ $rowspan }}" style="border-bottom: 1px solid #fff !important;"></td>
            <td style="">Total Amount</td>
            <td>{{ number_format($total,2) }}</td>
        </tr>
        @if($order->previous_due>0)
            <tr>
                <td>Previous Due</td>
                <td>{{ number_format($order->previous_due,2) }}</td>
            </tr>
        @endif
        @if($order->return_amount>0)
            <tr>
                <td>Return Amount</td>
                <td>{{ number_format($order->return_amount,2) }}</td>
            </tr>
        @endif
        @if($order->transport_cost>0)
            <tr>
                <td>Transport Cost</td>
                <td>{{ number_format($order->transport_cost,2) }}</td>
            </tr>
        @endif
        @if($order->sale_adjustment>0)
            <tr>
                <td>Sale Adjustment</td>
                <td>{{ number_format($order->sale_adjustment,2) }}</td>
            </tr>
        @endif
        @if($order->discount>0)
            <tr>
                <td>Discount</td>
                <td>{{ number_format($order->discount,2) }}</td>
            </tr>
        @endif
        <tr>
            <td style="">Payment</td>
            <td>{{ number_format($order->paid,2) }}</td>
        </tr>
        <tr>
            <td style="">Totak Due</td>
            <td>{{ number_format($order->current_due,2) }}</td>
        </tr>
    </table>
@endif

<div class="divFooter" style="width: 100%">
    <div class="row" style="">
        <div class="col-xs-9" style="height: 20px;">
            <span style="border-top: 1px dotted black">Customer Signature</span>
        </div>
        <div class="col-xs-3 text-right">
            <span style="border-top: 1px dotted black">Authorised Signature</span>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-xs-12 text-center">
            Software developed by Tech&Byte. Mobile: +8801521499793,+8801603278404
        </div>
    </div>
</div>


<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
