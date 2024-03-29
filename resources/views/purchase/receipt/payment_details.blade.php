@extends('layouts.app')

@section('style')
    <style>
        #receipt-content{
            font-size: 18px;
        }

        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid black !important;
        }
    </style>
@endsection

@section('title')
    Payment Details
@endsection

@section('content')
    <div class="" id="receipt-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('purchase_receipt.payment_print', ['payment' => $payment->id]) }}" class="btn btn-primary">Print</a>
                        </div>
                    </div>

                    <hr>

                    <div class="">
                        <div class="col-xs-4">
                            <img src="{{ asset('img/company.png') }}" height="50px" style="float: left">
                            <h2 style="margin: 0px; float: left">RECEIPT</h2>
                        </div>

                        <div class="col-xs-4 text-center">
                            <b>Date: </b> {{ $payment->date->format('j F, Y') }}
                        </div>

                        <div class="col-xs-4 text-right">
                            <b>No: </b> {{ str_pad($payment->id, 5, 0, STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <div class="" style="margin-top: 20px">
                        <div class="col-xs-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="20%">
                                        @if($payment->type == 1)
                                            To
                                        @elseif($payment->type == 2)
                                            From
                                        @endif
                                    </th>
                                    <td>{{ $payment->supplier->name??'' }}</td>
                                    <th width="10%">Amount</th>
                                    <td width="15%">Tk {{ number_format($payment->amount, 2) }}</td>
                                </tr>

                                <tr>
                                    <th>Amount (In Word)</th>
                                    <td colspan="3">{{ $payment->amount_in_word }}</td>
                                </tr>

                                {{-- <tr>
                                    <th>For Payment of</th>
                                    <td colspan="3">Order No. {{ $payment->order_no }}</td>
                                </tr> --}}

                                <tr>
                                    <th>Paid By</th>
                                    <td colspan="3">
                                        @if($payment->transaction_method == 1)
                                            Cash
                                        @elseif($payment->transaction_method == 3)
                                            Mobile Banking
                                        @elseif($payment->transaction_method == 4)
                                            Account Adjustment Discount
                                        @elseif($payment->transaction_method == 5)
                                            Purchase Adjustment Discount
                                        @elseif($payment->transaction_method == 6)
                                            Purchase Return Adjustment
                                        @else
                                            Bank - {{ $payment->bank->name.' - '.$payment->branch->name.' - '.$payment->account->account_no }}
                                        @endif
                                    </td>
                                </tr>

                                @if($payment->transaction_method == 2)
                                    <tr>
                                        <th>Cheque No.</th>
                                        <td colspan="3">{{ $payment->cheque_no }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <th>Note</th>
                                    <td colspan="3">{{ $payment->note }}</td>
                                </tr>

                                @if($payment->transaction_method == 2)
                                    <tr>
                                        <th>Cheque Image</th>
                                        <td colspan="3" class="text-center">
                                            <img src="{{ asset($payment->cheque_image) }}" height="300px">
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
