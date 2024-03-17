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
<header id="pageHeader" style="margin-bottom: 5px;">
    <div class="row" style="border-bottom: 1px solid #000;">
        <div class="col-xs-3 text-center">
{{--            <div style="">--}}
{{--                <h1 style="margin-top: 10px;"><strong style="font-size: 60px;letter-spacing: 5px;padding: 5px 20px;border-radius: 30px 0px;">MT</strong></h1>--}}
{{--            </div>--}}
        </div>

        <div class="col-xs-6 text-center">
            <p style="margin-bottom: 0px;">বিসমিল্লাহির রাহমানীর রাহীম</p>
            <h1 style="margin-top: 6px;margin-bottom: 0;"><strong style="font-size: 40px;">মেঘা ফুটওয়্যার</strong></h1>
            <p style="margin-bottom: 0;">মেঘা ট্রেডিং, ১৭৪, সিদ্দিক বাজার ঢাকা- ১০০০</p>
            <p style="margin-bottom: 0;">হটলাইন: ০১৮৪১৫০৯২৬৩ ফোন: ০২২২৬৬৩৮৩৩৩</p>
            <p style="margin-bottom: 0;">০১৭২০০০৯২৬৩</p>
            <p style="margin-bottom: 0;">বিন নং: ০০১০৬৭১৫৪-০২০৫</p>
        </div>
        <div class="col-xs-3 text-center">
            <div style="">
{{--                <h2 style="margin-top: 10px;"><strong style="letter-spacing: 5px;">INVOICE</strong></h2>--}}
                <img src="{{ asset('img/company.png') }}" style="height: 80px;"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3">
        </div>
        <div class="col-xs-6 text-center" style="">
            <div>
                <strong style="font-size: 25px;">
                    <i>ক্যাশ মেমো/বিল</i>
                </strong>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="row" style="">
        <div class="col-xs-8">
            <table>
                <tr>
                    <td style="border-right: 1px solid black;padding-right: 30px;">গ্রাহক আই.ডি.</td>
                    <td style="padding-left: 30px;">#{{ enNumberToBn($order->customer->id_no??'') }}</td>
                </tr>
                <tr>
                    <th style="border-right: 1px solid black;">গ্রাহক নাম</th>
                    <th style="padding-left: 30px;"><i>{{ $order->customer->name??'' }}</i></th>
                </tr>
                <tr>
                    <td style="border-right: 1px solid black;">গ্রাহকের ঠিকানা</td>
                    <td style="padding-left: 30px;">{{ $order->customer->address??'' }}</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid black;">মোবাইল</td>
                    <td style="padding-left: 30px;">{{ enNumberToBn($order->customer->mobile_no??'') }}</td>
                </tr>
            </table>
        </div>
        <div class="col-xs-4">
            <table>
                <tr>
                    <td style="border-right: 1px solid black;padding-right: 30px;">বিল নং</td>
                    <td style="padding-left: 30px;">{{ enNumberToBn($order->order_no) }}</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid black;">তারিখ</td>
                    <td style="padding-left: 30px;">{{ enNumberToBn(date('d-m-Y',strtotime($order->date))) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

@php
    $totalQuantity = 0;
@endphp

@if(count($order->products) > 0)
    <br>
    <table class="table table-bordered product-table pt-4" style="margin-top: 5px; margin-bottom: 1px !important; font-size: 12px;">
        <thead>
        <tr>
            <th class="text-center" style="border: 1px solid black !important;">ক্রম</th>
            <th colspan="2" style="border: 1px solid black !important;"> পণ্যের বিবরণ</th>
            <th style="border: 1px solid black !important;"> জোড়া </th>
            <th class="text-right" style="border: 1px solid black !important;">দাম</th>
            <th class="text-right" style="border: 1px solid black !important;">বিক্রয় মূল্য</th>
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
                <td class="text-center" style="border-radius: 10px 0px 10px 10px !important;">{{ enNumberToBn($key+1) }}</td>
                <td  colspan="2">
                    {{ enNumberToBn($item->productItem->name??'') }} - {{ enNumberToBn($item->productCategory->name??'') }}
                </td>
                <td>
                    {{ enNumberToBn($item->quantity) }}
                </td>
                <td class="text-right" width="100">
                    @if(auth()->user()->role == 2)
                        {{ enNumberToBn(number_format($item->buy_price, 2)) }}
                    @else
                        {{ enNumberToBn(number_format($item->unit_price, 2)) }}
                    @endif
                </td>
                <td class="text-right" width="100">
                    @if(auth()->user()->role == 2)
                        {{ enNumberToBn(number_format(($item->buy_price) * $item->quantity, 2)) }}
                    @else
                        {{ enNumberToBn(number_format($item->total, 2)) }}
                    @endif
                </td>
            </tr>
        @endforeach
{{--        <tr>--}}
{{--            <td style="">আজকের বিল</td>--}}
{{--            <td style="">{{ enNumberToBn(number_format($subTotal,2)) }}</td>--}}
{{--            <td style="" rowspan="3">মোট জোড়া</td>--}}
{{--            <td style="" rowspan="3">{{ enNumberToBn($totalQuantity) }}</td>--}}
{{--            <th>সর্বমোট</th>--}}
{{--            <th>{{ enNumberToBn(number_format($subTotal,2)) }}</th>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td style="">নগদ প্রদান</td>--}}
{{--            <td style="">{{ enNumberToBn(number_format($order->paid,2)) }}</td>--}}
{{--            <td>কমিশন</td>--}}
{{--            <td>{{ enNumberToBn(number_format($order->discount,2)) }}</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <th style="">পূর্বের বকেয়া</th>--}}
{{--            <th style="">{{ enNumberToBn(number_format($order->previous_due,2)) }}</th>--}}
{{--            <td>পরিবহন</td>--}}
{{--            <td>{{ enNumberToBn(number_format($order->transport_cost,2)) }}</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <th style="">মোট বকেয়া</th>--}}
{{--            <th style="">{{ enNumberToBn(number_format($order->current_due,2)) }}</th>--}}
{{--            <td></td>--}}
{{--            <td></td>--}}
{{--            <td>নিট মূল্য</td>--}}
{{--            <td>{{ enNumberToBn(number_format($total,2)) }}</td>--}}
{{--        </tr>--}}
    </table>
@endif

@php
    $totalQuantity = 0;
@endphp

@if(count($order->products) > 0)
    <br>
    <table class="table table-bordered product-table pt-4" style="margin-top: 5px; margin-bottom: 1px !important; font-size: 12px;">
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th class="text-center" style="border: 1px solid black !important;">ক্রম</th>--}}
{{--            <th colspan="2" style="border: 1px solid black !important;"> পণ্যের বিবরণ</th>--}}
{{--            <th style="border: 1px solid black !important;"> জোড়া </th>--}}
{{--            <th class="text-right" style="border: 1px solid black !important;">দাম</th>--}}
{{--            <th class="text-right" style="border: 1px solid black !important;">বিক্রয় মূল্য</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
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
{{--            <tr class="{{ $key==26?'pagebreak':'' }}">--}}
{{--                <td class="text-center" style="border-radius: 10px 0px 10px 10px !important;">{{ enNumberToBn($key+1) }}</td>--}}
{{--                <td  colspan="2">--}}
{{--                    {{ enNumberToBn($item->productItem->name??'') }} - {{ enNumberToBn($item->productCategory->name??'') }}--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{ enNumberToBn($item->quantity) }}--}}
{{--                </td>--}}
{{--                <td class="text-right" width="100">--}}
{{--                    @if(auth()->user()->role == 2)--}}
{{--                        {{ enNumberToBn(number_format($item->buy_price, 2)) }}--}}
{{--                    @else--}}
{{--                        {{ enNumberToBn(number_format($item->unit_price, 2)) }}--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--                <td class="text-right" width="100">--}}
{{--                    @if(auth()->user()->role == 2)--}}
{{--                        {{ enNumberToBn(number_format(($item->buy_price) * $item->quantity, 2)) }}--}}
{{--                    @else--}}
{{--                        {{ enNumberToBn(number_format($item->total, 2)) }}--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--            </tr>--}}
        @endforeach
        <tr>
            <td style="">আজকের বিল</td>
            <td style="">{{ enNumberToBn(number_format($subTotal,2)) }}</td>
            <td style="" rowspan="3">মোট জোড়া</td>
            <td style="" rowspan="3">{{ enNumberToBn($totalQuantity) }}</td>
            <th>সর্বমোট</th>
            <th>{{ enNumberToBn(number_format($subTotal,2)) }}</th>
        </tr>
        <tr>
            <td style="">নগদ প্রদান</td>
            <td style="">{{ enNumberToBn(number_format($order->paid,2)) }}</td>
            <td>কমিশন</td>
            <td>{{ enNumberToBn(number_format($order->discount,2)) }}</td>
        </tr>
        <tr>
            <th style="">পূর্বের বকেয়া</th>
            <th style="">{{ enNumberToBn(number_format($order->previous_due,2)) }}</th>
            <td>পরিবহন</td>
            <td>{{ enNumberToBn(number_format($order->transport_cost,2)) }}</td>
        </tr>
        <tr>
            <th style="">মোট বকেয়া</th>
            <th style="">{{ enNumberToBn(number_format($order->current_due,2)) }}</th>
            <td></td>
            <td></td>
            <td>নিট মূল্য</td>
            <td>{{ enNumberToBn(number_format($total,2)) }}</td>
        </tr>
    </table>
    @php
        $numto = new \Rakibhstu\Banglanumber\NumberToBangla();
    @endphp
    <div class="row">
        <div class="col-xs-1">
            @if($order->current_due>0)
                <h3 style="margin-top: 0;"><strong style="background-color: #e7e9db !important;">DUE</strong></h3>
            @else
                <h3 style="margin-top: 0;"><strong style="background-color: #e7e9db !important;">PAID</strong></h3>
            @endif
        </div>
        <div class="col-xs-5">
            <p style="">নোট: {{ $order->note }}</p>
        </div>
        <div class="col-xs-6 text-right">
            @php
               $due = round($order->current_due);
            @endphp
            <p style="">কথায়: {{ $numto->bnMoney(intval($due)) }} মাত্র</p>
        </div>
    </div>
    <div class="row" style="margin-top: 50px;">
        <div class="col-xs-4">
            <span style="border-top: 1px solid black;">এন্ট্রিদাতা</span>
        </div>
        <div class="col-xs-4 text-center">
            <span style="border-top: 1px solid black">বিক্রয় প্রতিনিধির স্বাক্ষর</span>
        </div>
        <div class="col-xs-4 text-right">
            <span style="border-top: 1px solid black">ক্রেতার স্বাক্ষর</span>
        </div>
    </div>
    <div class="row" style="margin-top: 30px;">
        <div class="col-xs-10 text-center" style="border: 1px solid black;margin-left: 60px;">
            <span style="">ধন্যবাদ আবার আসবেন {{ enNumberToBn(date('Y')) }} ।</span>
        </div>
    </div>
@endif

<div class="divFooter" style="width: 100%">

    <div class="row" style="border: 1px solid black;">
        <div class="col-xs-6 text-center" style="padding: 17px 0px;font-size: 10px;background-color: #0ac282 !important;">

        </div>
        <div class="col-xs-6 text-center" style="padding: 5px 0px;font-size: 17px;border-left: 1px solid black;background-color: #e63003 !important;">
            <i style="color: #fff !important;">Trip By Megha</i>
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
