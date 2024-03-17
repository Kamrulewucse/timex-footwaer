@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>
        .table tr th, .table tr td {
            font-size: 10px;
        }
    </style>
@endsection

@section('title')
    Sale Receipt Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('sale_receipt.print', ['order' => $order->id]) }}" id="{{ request()->get('receipt')==1?'invoice':'' }}" class="btn btn-success invoice"> Print </a>
                        </div>
                    </div>
                    <header id="pageHeader" style="margin-bottom: 5px;">
                        <div class="row" style="border-bottom: 1px solid #000;">
                            <div class="col-md-3 text-center">
                                {{--            <div style="">--}}
                                {{--                <h1 style="margin-top: 10px;"><strong style="font-size: 60px;letter-spacing: 5px;padding: 5px 20px;border-radius: 30px 0px;">MT</strong></h1>--}}
                                {{--            </div>--}}
                            </div>

                            <div class="col-md-6 text-center" style="font-size: 10px;">
                                <p style="margin-bottom: -30px;">বিসমিল্লাহির রাহমানীর রাহীম</p>
                                <h1 style="margin-top: 6px;margin-bottom: -5px;"><strong style="font-size: 20px;">মেঘা ফুটওয়্যার</strong></h1>
                                <p style="margin-bottom: 0;">মেঘা ট্রেডিং, ১৭৪, সিদ্দিক বাজার ঢাকা- ১০০০</p>
                                <p style="margin-bottom: 0;">হটলাইন: ০১৮৪১৫০৯২৬৩ ফোন: ০২২২৬৬৩৮৩৩৩, ০১৭২০০০৯২৬৩</p>
                                <p style="margin-bottom: 0;">বিন নং: ০০১০৬৭১৫৪-০২০৫</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div style="">
                                    {{--                <h2 style="margin-top: 10px;"><strong style="letter-spacing: 5px;">INVOICE</strong></h2>--}}
                                    <img src="{{ asset('img/company.png') }}" style="height: 80px;"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-6 text-center" style="">
                                <div>
                                    <strong style="font-size: 15px;">
                                        <i>ক্যাশ মেমো/বিল</i>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </header>
                    <div class="container-fluid">
                        <div class="row" style="">
                            <div class="col-md-8">
                                <table style="font-size: 10px;">
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
                            <div class="col-md-4">
                                <table  style="font-size: 10px;">
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
                        <table class="table table-bordered product-table" style="margin-bottom: 1px !important; font-size: 10px;">
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
{{--                            <tr>--}}
{{--                                <td style="">আজকের বিল</td>--}}
{{--                                <td style="">{{ enNumberToBn(number_format($subTotal,2)) }}</td>--}}
{{--                                <td style="" rowspan="3">মোট জোড়া</td>--}}
{{--                                <td style="" rowspan="3">{{ enNumberToBn($totalQuantity) }}</td>--}}
{{--                                <th>সর্বমোট</th>--}}
{{--                                <th>{{ enNumberToBn(number_format($subTotal,2)) }}</th>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td style="">নগদ প্রদান</td>--}}
{{--                                <td style="">{{ enNumberToBn(number_format($order->paid,2)) }}</td>--}}
{{--                                <td>কমিশন</td>--}}
{{--                                <td>{{ enNumberToBn(number_format($order->discount,2)) }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th style="">পূর্বের বকেয়া</th>--}}
{{--                                <th style="">{{ enNumberToBn(number_format($order->previous_due,2)) }}</th>--}}
{{--                                <td>পরিবহন</td>--}}
{{--                                <td>{{ enNumberToBn(number_format($order->transport_cost,2)) }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th style="">মোট বকেয়া</th>--}}
{{--                                <th style="">{{ enNumberToBn(number_format($order->current_due,2)) }}</th>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td>নিট মূল্য</td>--}}
{{--                                <td>{{ enNumberToBn(number_format($total,2)) }}</td>--}}
{{--                            </tr>--}}
                        </table>

                        <table class="table table-bordered product-table" style="margin-bottom: 1px !important; font-size: 10px; margin-top: 15px;">
                            <?php
                            $subTotal = 0;
                            $total = 0;
                            $totalAmount = 0;
                            ?>
                            {{--        <tbody>--}}
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
                            <div class="col-md-1">
                                @if($order->current_due>0)
                                    <h3 style="margin-top: -14px;"><strong style="background-color: #e7e9db !important;font-size: 10px;">DUE</strong></h3>
                                @else
                                    <h3 style="margin-top: -14px;"><strong style="background-color: #e7e9db !important;font-size: 10px;">PAID</strong></h3>
                                @endif
                            </div>
                            <div class="col-md-5">
                                <p style="font-size: 10px;">নোট: {{ $order->note }}</p>
                            </div>
                            <div class="col-md-6 text-right">
                                @php
                                    $due = round($order->current_due);
                                @endphp
                              <p style="font-size: 10px;">কথায়: {{ $numto->bnMoney(intval($due)) }} মাত্র</p>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 0px;">
                            <div class="col-md-4">
                                <span style="border-top: 1px solid black;font-size: 10px;">এন্ট্রিদাতা</span>
                            </div>
                            <div class="col-md-4 text-center">
                                <span style="border-top: 1px solid black;font-size: 10px;">বিক্রয় প্রতিনিধির স্বাক্ষর</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span style="border-top: 1px solid black;font-size: 10px;">ক্রেতার স্বাক্ষর</span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 0px;">
                            <div class="col-md-10 text-center" style="border: 1px solid black;margin-left: 60px;">
                                <span style="font-size: 10px;">ধন্যবাদ আবার আসবেন {{ enNumberToBn(date('Y')) }} ।</span>
                            </div>
                        </div>
                    @endif

                    <div class="divFooter" style="width: 100%">

                        <div class="row" style="border: 1px solid black;">
                            <div class="col-md-6 text-center" style="height: 20px;font-size: 10px;background-color: #0ac282 !important;">

                            </div>
                            <div class="col-md-6 text-center" style="height: 20px;font-size: 17px;border-left: 1px solid black;background-color: #e63003 !important;">
                               <p style="margin-top: -6px;"><i style="color: #fff !important;font-size: 10px;">Trip By Megha</i></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center" style="font-size: 10px;">
                                Software developed by Tech&Byte. Mobile: +8801521499793,+8801603278404
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('#table-payments').DataTable({
                "order": false,
            });
        });
        document.getElementById('invoice').click();
    </script>
@endsection
