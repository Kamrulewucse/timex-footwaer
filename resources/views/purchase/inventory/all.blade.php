@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .table tr th,.table tr td{
            padding: 0px 2px;
            font-size: 12px;
            vertical-align: middle;
        }
        .btn-group-sm>.btn, .btn-sm {
            padding: 1px 0.5rem;
        }
    </style>
@endsection

@section('title')
    Purchase Inventory
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
                    <table id="table" class="table table-bordered table-striped" style="width: 100% !important">
                        <thead>

                        <tr>
{{--                            <th>Product Code</th>--}}
                            <th>Product Model </th>
                            <th>Product Size </th>
{{--                            <th>Warehouse</th>--}}
{{--                            <th>In QTY</th>--}}
{{--                            <th>Out QTY</th>--}}
                            <th>Present Qty</th>
                            <th>Unit Price</th>
                            <th>Retail Price</th>
                            <th>Wholesale price</th>
                            <th>Total Pur Amount</th>
                            @if(auth()->user()->role == 0)
                            <th>Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
{{--                            @foreach($inventories as $inventory)--}}
                                <?php
//{{--                                  $purchaseInSum = \App\Model\PurchaseInventoryLog::where('purchase_inventory_id', $inventory->id)->where('type', 1)->where('return_status', 0)->sum('quantity');--}}
//{{--                                  $purchaseRet= \App\Model\PurchaseInventoryLog::where('purchase_inventory_id', $inventory->id)->where('type', 1)->where('return_status', 1)->sum('quantity');--}}
//{{--                                  $purchaseOutSum = \App\Model\PurchaseInventoryLog::where('purchase_inventory_id', $inventory->id)->where('type', 2)->where('return_status', 0)->sum('quantity');--}}
                                ?>
{{--                                <tr>--}}
{{--                                    <td>{{$inventory->productItem->name??''}}</td>--}}
{{--                                    <td>{{$inventory->productCategory->name??''}}</td>--}}
{{--                                    <td>{{ $inventory->warehouse->name??''}}</td>--}}
{{--                                    <td>{{ $purchaseInSum ??''}}</td>--}}
{{--                                    <td>{{ $purchaseOutSum-$purchaseRet }}</td>--}}
{{--                                    <td>{{ $inventory->quantity??''}}</td>--}}
{{--                                    <td>{{ $inventory->unit_price??''}}</td>--}}
{{--                                    <td>{{ $inventory->selling_price??''}}</td>--}}
{{--                                    <td>{{ $inventory->quantity*$inventory->unit_price}}</td>--}}
{{--                                    @if(auth()->user()->role == 0)--}}
{{--                                        <td>--}}
{{--                                            <a href="{{ route('purchase_inventory.details', ['purchase_inventory' => $inventory->id])}}" class="btn btn-primary btn-sm">Details</a>--}}
{{--                                        </td>--}}
{{--                                    @endif--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-barcode">
        <div class="modal-dialog">
            <form action="{{ route('barcode_generate') }}" target="_blank">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"> Barcode </h4>
                    </div>
                    <div class="modal-body">
                        <form id="modal-form" enctype="multipart/form-data" name="modal-form">
                            <div class="form-group row">
                                <label> Product </label>
                                <input class="form-control" id="product_name" disabled>
                                <input type="hidden" class="form-control" id="purchase_inventory_id" name="purchase_inventory_id">
                            </div>


                            <div class="form-group row">
                                <label> Quantity </label>
                                <input class="form-control" name="quantity" value="1" placeholder="Quantity">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal"> Close </button>
                        <button type="submit" class="btn btn-primary" id="barcode_generate"> Create barcode </button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $(function () {
            //$('#table').DataTable({});
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('purchase_inventory.datatable') }}',
                columns: [
                    // {data: 'serial', name: 'serial'},
                    {data: 'product_item', name: 'product_item'},
                    {data: 'product_category', name: 'product_category'},
                    //{data: 'warehouse', name: 'warehouse'},
                    //{data: 'in_quantity', name: 'in_quantity'},
                    //{data: 'out_quantity', name: 'out_quantity'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'unit_price', name: 'unit_price'},
                    {data: 'selling_price', name: 'selling_price'},
                    {data: 'wholesale_price', name: 'wholesale_price'},
                    {data: 'total_pur_price', name: 'total_pur_price'},
                        @if(auth()->user()->company_branch_id == 0)
                    {data: 'action', name: 'action'},
                    @endif
                ],
            });

            $('body').on('click', '.barcode_modal', function () {
                var product_name = $(this).data('name')+' - '+$(this).data('code');
                var purchase_inventory_id = $(this).data('id');
                $('#product_name').val(product_name);
                $('#purchase_inventory_id').val(purchase_inventory_id);
                $('#modal-barcode').modal('show');
            });
        });
    </script>
@endsection
