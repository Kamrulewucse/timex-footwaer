@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Customer
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
                <div class="card-body">
                    <a class="btn btn-primary" href="{{ route('sales_return.add') }}"> Add Return Product</a>
                    <hr>
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped" style="width: 100% !important">
                            <thead>
                              <tr>
                                <th>Date</th>
                                <th> Customer </th>
                                <th> Code </th>
                                <th> Model </th>
                                <th> Category </th>
                                <th> Warehouse </th>
                                <th> Quantity </th>
                                <th> Unit Price (Sale) </th>
                                <th> Selling Price (Sale) </th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    
    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                // "order": [[ 1, "dsc" ]],
                order: false,
                //ordering: false,
                ajax: '{{ route("sales_return.datatable") }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'customer_name', name: 'customer.name'},
                    {data: 'serial', name: 'serial'},
                    {data: 'product_item.name', name: 'productItem.name'},
                    {data: 'product_category.name', name: 'productCategory.name'},
                    {data: 'warehouse.name', name: 'warehouse.name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'unit_price', name: 'unit_price'},
                    {data: 'selling_price', name: 'selling_price'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });
        })
    </script>
@endsection

