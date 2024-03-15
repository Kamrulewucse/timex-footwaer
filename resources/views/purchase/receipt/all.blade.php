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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
{{--                    <a class="btn btn-warning btn-sm" target="_blank" href="{{route('purchase_receipt.view_trash')}}">View Trash</a>--}}
{{--                    <form action="{{route('purchase_import')}}" method="POST" enctype="multipart/form-data">--}}
{{--                        @csrf--}}
{{--                        <input type="file" name="file" style="padding: .2rem .75rem !important;width: 50% !important;" class="form-control">--}}
{{--                        <br>--}}
{{--                        <button class="btn btn-success">Import Purchase Data</button>--}}
{{--                    </form>--}}
{{--                    <form enctype="multipart/form-data" action="{{ route('purchase_import') }}" class="form-horizontal" method="post" style="background-color: #143257;padding-top: 10px;border-radius: 5px;">--}}
{{--                        @csrf--}}
{{--                        <div class="card-body">--}}
{{--                            <div class=" row {{ $errors->has('excel_file') ? 'has-error' :'' }}">--}}
{{--                                <label for="excel_file" style="color: #fff;" class="col-sm-2">Purchase Excel File <span class="text-danger">*</span></label>--}}
{{--                                <div class="col-sm-7">--}}
{{--                                    <input type="file" style="padding: 0px .75rem !important;width: 50% !important;" name="excel_file" class="form-control" id="excel_file">--}}
{{--                                    @error('excel_file')--}}
{{--                                    <span class="text-danger">{{ $message }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-3">--}}
{{--                                    <a class="btn btn-danger" href="{{ asset('excel/purchase-order.xlsx') }}">--}}
{{--                                            <i class="fas fa-cloud-download-alt"></i>--}}
{{--                                            Download Demo Excel--}}
{{--                                        </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-body -->--}}
{{--                        <div class="card-footer">--}}
{{--                            <button type="submit" class="btn btn-primary">Import</button>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-footer -->--}}
{{--                    </form>--}}
{{--                    <hr>--}}
                    <table id="table" class="table table-bordered table-striped" style="width: 100% !important">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order No</th>
                            <th>Supplier</th>
                            <th>Warehouse</th>
                            <th>Quantity</th>
                            <th>Total</th>
{{--                            <th>Product Model</th>--}}
                            {{-- <th>Paid</th>
                            <th>Due</th> --}}
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-danger fade" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Delete Receipt</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
                <div class="modal-body">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-light" id="modalBtnDelete">Delete</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('script')
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        var selectedOrderId;

        $('body').on('click', '.btnDelete', function () {
            //alert('jiji');
            $('#modal-delete').modal('show');
            selectedOrderId = $(this).data('id');
        });

        $('#modalBtnDelete').click(function () {
            $.ajax({
                method: "POST",
                url: "{{ route('purchase_order.delete') }}",
                data: { id: selectedOrderId }
            }).done(function( msg ) {
                location.reload();
            });
        });
    </script>

    <script>
        $(function () {
            var selectedOrderId;

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('purchase_receipt.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'order_no', name: 'order_no'},
                    {data: 'supplier', name: 'supplier.name'},
                    {data: 'warehouse', name: 'warehouse'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'total', name: 'total'},
                    // {data: 'product_items', name: 'product_items'},
                    // {data: 'paid', name: 'paid'},
                    // {data: 'due', name: 'due'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[ 0, "desc" ]],
            });
        });
    </script>
@endsection
