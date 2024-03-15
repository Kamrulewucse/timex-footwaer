@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Purchase Receipt Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('purchase_receipt.print', ['order' => $order->id]) }}" class="btn btn-primary">Print</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order No.</th>
                                    <td>{{ $order->order_no }}</td>
                                </tr>
                                <tr>
                                    <th>Order Date</th>
                                    <td>{{ $order->date->format('j F, Y') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6 table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" class="text-center">Supplier Info</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $order->supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>{{ $order->supplier->mobile }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $order->supplier->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
{{--                                        <th> Code </th>--}}
                                        <th> Model </th>
                                        <th> Size </th>
                                        <th> Warehouse </th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Selling Price</th>
                                        <th>Wholesale Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                @php
                                $totalQuantity = 0;
                                @endphp

                                <tbody>
                                    @foreach($order->products as $product)
                                        @php
                                            $totalQuantity += $product->quantity;
                                        @endphp
                                        <tr>
{{--                                            <td>{{ $product->serial }}</td>--}}
                                            <td>{{ $product->productItem->name??'' }}</td>
                                            <td>{{ $product->productCategory->name??'' }}</td>
                                            <td>{{ $product->warehouse->name??'' }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>Tk {{ number_format($product->unit_price, 2) }}</td>
                                            <td>Tk {{ number_format($product->selling_price, 2) }}</td>
                                            <td>Tk {{ number_format($product->wholesale_price, 2) }}</td>
                                            <td>Tk {{ number_format($product->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                <tr>
                                    <th colspan="2"></th>
                                    <th>Total</th>
                                    <td>{{$totalQuantity}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="offset-8 col-md-4 table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Amount</th>
                                    <td>Tk {{ number_format($order->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th> Transport Cost </th>
                                    <td>Tk {{ number_format($order->transport_cost, 2) }}</td>
                                </tr>
                                <tr>
                                    <th> Discount </th>
                                    <td>Tk {{ number_format($order->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th> Total </th>
                                    <td>Tk {{ number_format($order->total, 2) }}</td>
                                </tr>
                                 <tr>
                                    <th>Paid</th>
                                    <td>Tk {{ number_format($order->paid, 2) }}</td>
                                </tr>
                                 <tr>
                                    <th>Due</th>
                                    <td>Tk {{ number_format($order->due, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Refunds</th>
                                    <td>Tk {{ number_format($order->refund, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table id="table-payments" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Transaction Method</th>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                    <th>Account</th>
                                    <th>Amount</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($order->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->date->format('Y-m-d') }}</td>
                                        <td>
                                            @if($payment->type == 1)
                                                Pay
                                            @elseif($payment->type == 2)
                                                Refund
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->transaction_method == 1)
                                                Cash
                                            @elseif($payment->transaction_method == 4)
                                                Condition (Cash)
                                            @elseif($payment->transaction_method == 3)
                                                Mobile Banking
                                            @else
                                                Bank
                                            @endif
                                        </td>
                                        <td>{{ $payment->bank ? $payment->bank->name : '' }}</td>
                                        <td>{{ $payment->branch ? $payment->branch->name : '' }}</td>
                                        <td>{{ $payment->account ? $payment->account->account_no : '' }}</td>
                                        <td>Tk {{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->note }}</td>
                                        <td>
                                            <a href="{{ route('purchase_receipt.payment_details', ['payment' => $payment->id]) }}" class="btn btn-primary btn-sm">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#table-payments').DataTable({
                "order": false,
            });
        });
    </script>
@endsection
