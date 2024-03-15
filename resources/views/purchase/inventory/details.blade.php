@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <style>
        .your-choice-class {
            color: blue;
        }

        .your-choice-main {
            color: #18740a;
            font-weight: bold;
        }

        .your-choice-plus-class {
            color: #ef0409;
        }
    </style>
@endsection

@section('title')
    Purchase Inventory Details - {{ $purchase_inventory->productItem->name??'' }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Filter</h3>
                </div>
                <!-- /.box-header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label>Date</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="date" autocomplete="off">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                                <label>Type</label>

                                <select class="form-control select2" id="type">
                                    <option value="">All Type</option>
                                    <option value="1">In</option>
                                    <option value="2">Out</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="table" class="table table-bordered table-striped" style="width: 100% !important">
                        <thead>
                            <tr>
                                <th> Date </th>
{{--                                <th> Product code </th>--}}
                                <th> Type </th>
                                <th> Quantity </th>
                                <th> Unit Price </th>
                                <th> Selling Price </th>
                                <th> Supplier </th>
                                <th> Warehouse </th>
                                <th> Customer </th>
                                <th> Branch </th>
                                <th> Invoice No </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(function () {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('purchase_inventory.details.datatable') }}",
                    data: function (d) {
                        d.purchase_inventory_id = '{{ $purchase_inventory->id }}';
                        d.date = $('#date').val();
                        d.type = $('#type').val();
                    }
                },
                columns: [
                    // {data: 'id', name: 'id'},
                    {data: 'date', name: 'date'},
                    // {data: 'serial', name: 'purchaseInventory.serial'},
                    {data: 'type', name: 'type'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'unit_price', name: 'unit_price'},
                    {data: 'selling_price', name: 'selling_price'},
                    {data: 'supplier', name: 'supplier.name'},
                    {data: 'warehouse', name: 'warehouse.name'},
                    {data: 'customer', name: 'customer.name'},
                    {data: 'branch', name: 'branch.name'},
                    {data: 'action', name: 'action'},
                ],
                order: [[ 0, "desc" ]],
            });

            //Date range picker
            $('#date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                table.ajax.reload();
            });

            $('#date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                table.ajax.reload();
            });

            $('#date, #type').change(function () {
                table.ajax.reload();
            });
        })
    </script>
@endsection
