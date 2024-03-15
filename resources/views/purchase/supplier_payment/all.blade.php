@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Supplier Payment
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <select class="form-control supplier select2" name="supplier">
                    <option value="0">Select Option</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            {{--<th>Company Name</th>--}}
                            <th>Mobile</th>
                            <th> Total </th>
                            <th> Paid </th>
                            <th> Opening Due </th>
                            <th> Due </th>
                            {{--<th>Refund</th>--}}
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
{{--                        @foreach($suppliers as $supplier)--}}
{{--                            <tr>--}}
{{--                                <td>{{ $supplier->name }}</td>--}}
{{--                                --}}{{--<td>{{ $supplier->company_name }}</td>--}}
{{--                                <td>{{ $supplier->mobile }}</td>--}}
{{--                                <td>Tk {{ number_format($supplier->total, 2) }}</td>--}}
{{--                                <td>Tk {{ number_format($supplier->paid, 2) }}</td>--}}
{{--                                <td>Tk {{ number_format($supplier->opening_due, 2) }}</td>--}}
{{--                                <td>Tk {{ number_format($supplier->due, 2) }}</td>--}}
{{--                                --}}{{--<td>Tk {{ number_format($supplier->refund, 2) }}</td>--}}
{{--                                <td>--}}
{{--                                    <a class="btn btn-info btn-sm btn-pay" role="button" data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}">Payment</a>--}}
{{--                                    <a class="btn btn-primary btn-sm" href="{{ route('supplier_payments', ['supplier' => $supplier->id]) }}">Details</a>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Payment Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-form" enctype="multipart/form-data" name="modal-form">
                        <input type="hidden" name="supplier_id" id="supplier_id" value="">
                        <div class="form-group row">
                            <label>Name</label>
                            <input class="form-control" id="modal-name" disabled>
                        </div>

                        <div id="modal-order-info" style="background-color: lightgrey; padding: 10px; border-radius: 3px;"></div>

                        <div class="form-group row">
                            <label>Payment Type</label>
                            <select class="form-control select2" id="modal-pay-type" name="payment_type">
                                <option value="1">Cash</option>
                            </select>
                        </div>

                        <div class="form-group row">
                            <label>Amount</label>
                            <input class="form-control" name="amount" placeholder="Enter Amount">
                        </div>

                        <div class="form-group row">
                            <label>Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="date" name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
                            </div>
                            <!-- /.input group -->
                        </div>

                        <div class="form-group row">
                            <label>Note</label>
                            <input class="form-control" name="note" placeholder="Enter Note">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-pay">Pay</button>
{{--                    <a class="btn btn-info btn-sm btn-confirm-pay" role="button">Pay</a>--}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('script')
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $(function () {
            let dataTable = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('supplier_payment_datatable') }}',
                    type: 'GET',
                    data: function (d) {
                        d.supplier = $('.supplier').val()
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'total', name: 'total', orderable: false},
                    {data: 'paid', name: 'paid', orderable: false},
                    {data: 'opening_due', name: 'opening_due', orderable: false},
                    {data: 'due', name: 'due', orderable: false},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });

            $('.supplier').change(function () {
                dataTable.ajax.reload();
            });
            //Date picker
            $('#date, #date-refund').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('body').on('click', '.btn-pay', function () {
                var supplierId = $(this).data('id');
                var supplierName = $(this).data('name');
                $('#modal-order').html('<option value="">Select Order</option>');
                $('#modal-order-info').hide();
                $('#modal-name').val(supplierName);
                $('#supplier_id').val(supplierId);
                $('#modal-pay').modal('show');
                // $.ajax({
                //     method: "GET",
                //     url: "{{ route('supplier_payment.get_orders') }}",
                //     data: { supplierId: supplierId }
                // }).done(function( response ) {
                //     $.each(response, function( index, item ) {
                //         $('#modal-order').append('<option value="'+item.id+'">'+item.order_no+'</option>');
                //     });

                //     $('#modal-pay').modal('show');
                // });
            });

            $('#modal-btn-pay').click(function () {
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'

                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData($('#modal-form')[0]);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('supplier_payment.make_payment') }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.success) {
                                    $('#modal-pay').modal('hide');
                                    Swal.fire(
                                        'Paid!',
                                        response.message,
                                        'success'
                                    ).then((result) => {
                                        //location.reload();
                                        window.location.href = response.redirect_url;
                                    });

                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: response.message,
                                    });
                                }
                            }
                        });
                    }
                })
            });

            $('#modal-pay-type').change(function () {
                if ($(this).val() == '2') {
                    $('#modal-bank-info').show();
                } else {
                    $('#modal-bank-info').hide();
                }
            });

            $('#modal-pay-type').trigger('change');

            $('#modal-order').change(function () {
                var orderId = $(this).val();
                $('#modal-order-info').hide();

                if (orderId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('supplier_payment.order_details') }}",
                        data: { orderId: orderId }
                    }).done(function( response ) {
                        //$('#modal-order-info').html('<strong>Total: </strong>Tk '+parseFloat(response.total).toFixed(2)+' <strong>Paid: </strong>Tk '+parseFloat(response.paid).toFixed(2)+' <strong>Due: </strong>Tk '+parseFloat(response.due).toFixed(2)+' <strong>Refund: </strong>Tk '+parseFloat(response.refund).toFixed(2));
                        $('#modal-order-info').html('<strong>Total: </strong>Tk '+parseFloat(response.total).toFixed(2)+' <strong>Paid: </strong>Tk '+parseFloat(response.paid).toFixed(2)+' <strong>Due: </strong>Tk '+parseFloat(response.due).toFixed(2));
                        $('#modal-order-info').show();
                    });
                }
            });

            $('.modal-bank').change(function () {
                var bankId = $(this).val();
                $('.modal-branch').html('<option value="">Select Branch</option>');
                $('.modal-account').html('<option value="">Select Account</option>');

                if (bankId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_branch') }}",
                        data: { bankId: bankId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            $('.modal-branch').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('.modal-branch').trigger('change');
                    });
                }

                $('.modal-branch').trigger('change');
            });

            $('.modal-branch').change(function () {
                var branchId = $(this).val();
                $('.modal-account').html('<option value="">Select Account</option>');

                if (branchId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_bank_account') }}",
                        data: { branchId: branchId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            $('.modal-account').append('<option value="'+item.id+'">'+item.account_no+'</option>');
                        });
                    });
                }
            });
        })
    </script>
@endsection
