@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Purchase Receipt
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th class="btn btn-danger">Deleted Date</th>
                            <th>Receipt Date</th>
                            <th>Order No</th>
                            <th>Supplier</th>
                            <th>Warehouse</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Product Model</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($receipts as $receipt)
                            <tr>
                                <td class="btn btn-danger">{{ $receipt->deleted_at->format('Y-m-d') }}</td>
                                <td>{{ $receipt->date->format('Y-m-d') }}</td>
                                <td>{{ $receipt->order_no }}</td>
                                <td>{{ $receipt->supplier->name??'' }}</td>
                                <td>{{ $receipt->warehouse->name??'' }}</td>
                                <td>
                                    {{$receipt->quantity() ?? ''}}
                                </td>
                                <td>{{ number_format($receipt->total,2) }}</td>
                                <td>
                                    @php
                                        $products = '';
                                        foreach ($receipt->order_products as $key => $product) {
                                        $products .= $product->productItem->name??'';
                                        if(!empty($receipt->order_products[$key+1])){
                                        $products .= ', ';
                                        }
                                        }
                                    @endphp
                                    {{$products}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <p>{!! $receipts->render() !!}</p>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline" id="modalBtnDelete">Delete</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
