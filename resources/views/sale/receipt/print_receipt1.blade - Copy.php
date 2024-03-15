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
<header id="pageHeader" style="margin-bottom: 10px">
    <div class="row">
        <div class="col-xs-2 text-left">
            <div style="">
{{--                <strong style="font-size: 12px;">Gomti Footwear </strong>--}}
{{--                <p style="margin-bottom:0;font-size: 7px;">Ac No:1544204150234001</p>--}}
{{--                <p style="font-size: 8px;">Brac Bank LTD Dhaka</p>--}}
                <img width="50" src="{{ asset('img/gomti_one.png') }}">
            </div>
        </div>

        <div class="col-xs-8 text-center">
            <h1 style="margin-top: 6px;margin-bottom: 0;"><strong>গোমতি ফুটওয়্যার</strong></h1>
            <p style="margin-bottom: 0;">এখানে উন্নতমানের দেশী-বিদেশী,থাই,চায়না,বার্মিজ জুতা পাইকারি বিক্রয় করা হয় |</p>
            <strong>২১ ফুলবাড়ীয়া সিটি সুপার মার্কেট-১, ১ম তলা, গুলিস্তান ঢাকা-১০০০</strong>
            <p>মোবা: ০১৯৭২-২১৯৬০৬, ০১৮১৭-৪৫৩৮৩৭, ০১৯১১-১৫২১৬৪</p>
        </div>
        <div class="col-xs-2 text-right">
            <div style="">
{{--                <strong style="font-size: 12px;">Gomti Footwear </strong>--}}
{{--                <p style="margin-bottom:0;font-size: 7px;">Ac No:135412200212794</p>--}}
{{--                <p style="font-size: 8px;">Uttara Bank LTD Dhaka</p>--}}
                <img width="50" src="{{ asset('img/gomti_two.png') }}">
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="row" style="border: 1px solid black;margin-bottom: 5px;">
        <div class="col-xs-1 text-center" style="background-color: darkred !important;border: 3px solid black;height: 30px;">
            <div>
                <p style="color: white !important;padding-top: 3px;">নাম</p>
            </div>
        </div>
        <div class="col-xs-5" style="height: 30px;">
            <div>
                <p style="padding-top: 5px;font-weight: bold;">{{ $order->customer->name??'' }}</p>
            </div>
        </div>
        <div class="col-xs-1 text-center" style="background-color: darkred !important;border: 3px solid black;height: 30px;">
            <div>
                <p style="color: white !important;padding-top: 3px;">তারিখ</p>
            </div>
        </div>
        <div class="col-xs-5" style="height: 30px;">
            <div>
                <p style="padding-top: 5px;font-weight: bold;">{{ enNumberToBn(date('d-m-Y',strtotime($order->date))) }}</p>
            </div>
        </div>
    </div>
    <div class="row" style="border: 1px solid black;margin-bottom: 5px;">
        <div class="col-xs-2 text-center" style="background-color: darkred !important;height: 30px;border: 3px solid black;">
            <div>
                <p style="color: white !important;padding-top: 3px;">ঠিকানা</p>
            </div>
        </div>
        <div class="col-xs-4" style="height: 30px;">
            <div>
                <p style="padding-top: 5px;font-weight: bold;">{{ $order->customer->address??'' }}</p>
            </div>
        </div>
        <div class="col-xs-2 text-center" style="background-color: darkred !important;height: 30px;border: 3px solid black;">
            <div>
                <p style="color: white !important;padding-top: 3px;">মোবাইল</p>
            </div>
        </div>
        <div class="col-xs-4" style="height: 30px;">
            <div>
                <p style="padding-top: 5px;font-weight: bold;">{{ enNumberToBn($order->customer->mobile_no??'') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" style="background-color: darkred !important;height: 40px;">
            <div>
                <p style="padding-top: 9px;margin-left: 10px; color: #fff !important;">
                    ক্রঃ নং {{ enNumberToBn($order->order_no) }}
                </p>
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
            <th class="text-center" style="border: 3px solid black !important;background-color: darkred !important;color: white !important;"> সংখ্যা </th>
            <th style="border: 3px solid black !important;background-color: darkred !important;color: white !important;"> মালের বিবরণ </th>
            <th style="border: 3px solid black !important;background-color: darkred !important;color: white !important;"> জোড়া </th>
            <th class="text-right" style="border: 3px solid black !important;background-color: darkred !important;color: white !important;">দর</th>
            <th class="text-right" style="border: 3px solid black !important;background-color: darkred !important;color: white !important;">টাকা</th>
        </tr>
        </thead>
        <?php
        $subTotal = 0;
        $total = 0;
        $totalAmount = 0;
        ?>
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
                <td class="text-center" >{{ enNumberToBn($key+1) }}</td>
                <td>
                    {{ $item->productItem->name??'' }} - {{ $item->productCategory->name??'' }}
                </td>
                <td>
                    {{ enNumberToBn($item->quantity) }}
                </td>
                <td class="text-right" width="100">
                    @if(auth()->user()->role == 2)
                        Tk  {{ enNumberToBn(number_format($item->buy_price, 2)) }}
                    @else
                        Tk  {{ enNumberToBn(number_format($item->unit_price, 2)) }}
                    @endif
                </td>
                <td class="text-right" width="100">
                    @if(auth()->user()->role == 2)
                        Tk  {{ enNumberToBn(number_format(($item->buy_price) * $item->quantity, 2)) }}
                    @else
                        Tk  {{ enNumberToBn(number_format($item->total, 2)) }}
                    @endif
                </td>
            </tr>
        @endforeach
        <?php
            $rowspan = 3;
            if ($order->previous_due>0){$rowspan += 1;}
            if ($order->transport_cost>0){$rowspan += 1;}
            if ($order->discount>0){$rowspan += 1;}
        ?>
        <tr>
            <td rowspan="{{ $rowspan }}"></td>
            <td rowspan="{{ $rowspan }}"></td>
            <td rowspan="{{ $rowspan }}">{{ enNumberToBn($totalQuantity) }}</td>
            <td>মোট টাকা</td>
            <td>Tk  {{ enNumberToBn(number_format($total,2)) }}</td>
        </tr>
        @if($order->previous_due>0)
            <tr>
                <td>পূর্বের বকেয়া</td>
                <td>{{ enNumberToBn(number_format($order->previous_due,2)) }}</td>
            </tr>
        @endif
        @if($order->transport_cost>0)
            <tr>
                <td>ট্রান্সপোর্ট খরচ</td>
                <td>{{ enNumberToBn(number_format($order->transport_cost,2)) }}</td>
            </tr>
        @endif
        @if($order->discount>0)
            <tr>
                <td>ডিসকাউন্ট</td>
                <td>{{ enNumberToBn(number_format($order->discount,2)) }}</td>
            </tr>
        @endif
        <tr>
            <td>পরিশোধ</td>
            <td>Tk  {{ enNumberToBn(number_format($order->paid,2)) }}</td>
        </tr>
        <tr>
            <td>বকেয়া</td>
            <td>Tk  {{ enNumberToBn(number_format($order->current_due,2)) }}</td>
        </tr>

    </table>
@endif

<div class="divFooter" style="width: 100%">
    <div class="row" style="">
        <div class="col-xs-3">
            <span style="border-top: 1px solid black">ক্রেতার স্বাক্ষর</span>
        </div>
        <div class="col-xs-6">
            <div class="text-center" style="clear: both;">
                <strong>বিঃদ্রঃ বিক্রিত মাল ফেরত নেওয়া হয় না।</strong>
                <p>প্রতিটি মুসলিম নর-নারীর জন্য ইসলাম শিক্ষা ফরজ।</p>
            </div>
        </div>
        <div class="col-xs-3 text-right">
            <span style="border-top: 1px solid black">বিক্রেতার স্বাক্ষর</span>
        </div>
    </div>
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
