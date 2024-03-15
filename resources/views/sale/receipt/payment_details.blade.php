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
    <div class="row" id="receipt-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('sale_receipt.payment_print', ['payment' => $payment->id]) }}" class="btn btn-primary">Print</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <br>

                        <div class="col-xs-4 text-center">
                            <b>Date: </b> {{ $payment->date->format('j F, Y') }}
                        </div>

                        <div class="col-xs-4 text-right">
                            <b>No: </b> {{ str_pad($payment->id, 5, 0, STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="20%">
                                            From
                                    </th>
                                    <td>
                                        {{ $payment->customer->name??'' }}
                                    </td>
                                    <th width="15%">Amount</th>
                                    <td width="15%">Tk {{ number_format($payment->amount , 2) }}</td>
                                </tr>

                                <tr>
                                    <th>Amount (In Word)</th>
                                    <td colspan="3">{{ $payment->amount_in_word }}</td>
                                </tr>

                                {{-- <tr>
                                    <th>For Payment of</th>
                                    <td colspan="3">Order No. {{ $payment->salesOrder->order_no }}</td>
                                </tr> --}}

                                <tr>
                                    <th>Paid By</th>
                                    @if ($payment->status == 1 && $payment->transaction_method == 1)
                                        <td colspan="3">Cash Pending</td>
                                    @elseif($payment->status == 1 && $payment->transaction_method == 2)
                                        <td colspan="3">Bank-Cheque Pending</td>
                                    @else
                                        <td colspan="3">
                                            @if($payment->transaction_method == 1)
                                                Cash
                                            @elseif($payment->transaction_method == 3)
                                                Mobile Banking
                                            @elseif($payment->transaction_method == 4)
                                                Sale Adjustment Discount
                                            @elseif($payment->transaction_method == 5)
                                                Return Adjustment Amount
                                            @elseif(empty($payment->bank->name))
                                                Cheque CashIn
                                            @else
                                                Bank - {{ $payment->bank->name??''.' - '.$payment->branch->name??''.' - '.$payment->account->account_no??'' }}
                                            @endif
                                        </td>
                                    @endif
                                </tr>

                                @if ($payment->status == 1)
                                    <tr>
                                        <th>Pending Cheque No.</th>
                                        <td colspan="3">{{ $payment->cheque_no }}</td>
                                    </tr>
                                @else
                                    @if($payment->transaction_method == 2)
                                        <tr>
                                            <th>Cheque No.</th>
                                            <td colspan="3">{{ $payment->cheque_no }}</td>
                                        </tr>
                                    @endif

                                @endif

                                <tr>
                                    <th>Note</th>
                                    <td colspan="3">{{ $payment->note }}</td>
                                </tr>

                                @if($payment->remaining_due > $payment->customer->due)
                                    <tr>
                                        <th width="20%">
                                            Previous Due
                                        </th>
                                        <td>
                                            Tk {{ number_format($payment->remaining_due , 2) }}
                                        </td>
                                        <th width="15%">Current Due</th>
                                        <td width="15%">Tk {{ number_format($payment->customer->due , 2) }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <th width="20%">
                                            Current Due
                                        </th>
                                        <td>
                                            Tk {{ number_format($payment->remaining_due , 2) }}
                                        </td>
                                        <th width="15%">Due</th>
                                        <td width="15%">Tk {{ number_format($payment->customer->due , 2) }}</td>
                                    </tr>
                                @endif

                                @if ($payment->status == 2)

                                    @if($payment->transaction_method == 2)
                                        <tr>
                                            <th>Cheque Image</th>
                                            <td colspan="3" class="text-center">
                                                <img src="{{ asset($payment->cheque_image) }}" height="300px">
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
