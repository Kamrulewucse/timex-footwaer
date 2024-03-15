@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Sale Receipt
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
                    <div class="table-responsive">
{{--                        <a class="btn btn-sm btn-warning" target="_blank" href="{{route('sale_receipt.trash_view')}}">View Trash</a>--}}
                        <hr>
                        <table id="table" class="table table-bordered table-striped" style="width: 100% !important">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order No</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Phone</th>
{{--                                <th>Branch</th>--}}
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Previous Due</th>
                                <th>Return Amount</th>
                                <th>Discount</th>
                                <th>Transport Cost</th>
                                <th>Sale Adjustment</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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

@section('script')
 <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        var selectedOrderId;

        $('body').on('click', '.btnDelete', function () {
            $('#modal-delete').modal('show');
            selectedOrderId = $(this).data('id');
        });

        $('#modalBtnDelete').click(function () {
            $.ajax({
                method: "POST",
                url: "{{ route('sale_order.delete') }}",
                data: { id: selectedOrderId }
            }).done(function( msg ) {
                location.reload();
            });
        });
    </script>

    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('retail_sale_receipt.customer.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'order_no', name: 'order_no'},
                    {data: 'name', name: 'customer.name'},
                    {data: 'address', name: 'customer.address'},
                    {data: 'mobile', name: 'customer.mobile_no'},
                    // {data: 'company', name: 'companyBranch.name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'total', name: 'total'},
                    {data: 'previous_due', name: 'previous_due'},
                    {data: 'return_amount', name: 'return_amount'},
                    {data: 'discount', name: 'discount'},
                    {data: 'transport_cost', name: 'transport_cost'},
                    {data: 'sale_adjustment', name: 'sale_adjustment'},
                    {data: 'paid', name: 'paid'},
                    {data: 'due', name: 'due'},
                    // {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[ 0, "desc" ]],
            });
        });
    </script>
@endsection
