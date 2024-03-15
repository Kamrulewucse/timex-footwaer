@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Return Product Invoice
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('return_invoice.print', ['order' => $order->id]) }}" class="btn btn-primary">Print</a>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6 table-responsive">
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

                        <div class="col-md-6 table-responsive">
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
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
{{--                                    <th> Code </th>--}}
                                    <th> Model </th>
                                    <th> Size </th>
                                    <th> Warehouse </th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                @php
                                    $totalQuantity = 0;
                                    $totalAmount = 0;
                                @endphp
                                <tbody>
                                @foreach($order->logs as $log)
                                    @php
                                        $totalQuantity += $log->quantity;
                                        if (auth()->user()->role != 2)
                                            $totalAmount = $order->total;
                                        else
                                            $totalAmount += ($log->unit_price + nbrSellCalculation($log->unit_price)) * $log->quantity;


                                    @endphp
                                    <tr>
{{--                                        <td>{{ $log->serial }}</td>--}}
                                        <td>{{ $log->productItem->name??'' }}</td>
                                        <td>{{ $log->productCategory->name??'' }}</td>
                                        <td>{{ $log->warehouse->name??'' }}</td>
                                        <td>{{ $log->quantity }}</td>
                                        @if(auth()->user()->role == 2)
                                            <td>Tk {{ number_format($log->unit_price + nbrSellCalculation($log->unit_price), 2) }}</td>
                                            <td>Tk {{ number_format(($log->unit_price + nbrSellCalculation($log->unit_price)) * $log->quantity, 2) }}</td>
                                        @else
                                            <td>Tk {{ number_format($log->selling_price, 2) }}</td>
                                            <td>Tk {{ number_format($log->sale_total, 2) }}</td>
                                        @endif

                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Quantity</th>
                                    <td>{{ number_format($totalQuantity, 2) }}</td>
                                    <th class="text-right">Total Amount</th>
                                    <td>Tk {{ number_format($totalAmount, 2) }}</td>
                                </tr>
                                </tfoot>
                            </table>
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
    </script>
@endsection
