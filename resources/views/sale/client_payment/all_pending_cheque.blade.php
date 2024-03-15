@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Pending Cheque
@endsection

@section('content')
{{--    @if(Session::has('message'))--}}
{{--        <div class="alert alert-success alert-dismissible">--}}
{{--            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>--}}
{{--            {{ Session::get('message') }}--}}
{{--        </div>--}}
{{--    @endif--}}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap">Date</th>
                                    <th style="white-space: nowrap">Customer</th>
                                    <th style="white-space: nowrap">Phone</th>
                                    <th style="white-space: nowrap">Address</th>
{{--                                    <th style="white-space: nowrap">Branch</th>--}}
                                    <th style="white-space: nowrap">Payment Method</th>
                                    <th style="white-space: nowrap">Amount</th>
                                    <th style="white-space: nowrap">Note</th>
                                    <th style="white-space: nowrap">Status</th>
                                    <th style="white-space: nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td style="white-space: nowrap">{{ $payment->date->format('Y-m-d') }}</td>
                                    <td style="white-space: nowrap">{{ $payment->customer->name??'' }}</td>
                                    <td style="white-space: nowrap">{{ $payment->customer->mobile_no??'' }}</td>
                                    <td style="white-space: nowrap">{{ $payment->customer->address??'' }}</td>
{{--                                    <td style="white-space: nowrap">--}}
{{--                                      {{$payment->companyBranch->name ?? ''}}--}}
{{--                                    </td>--}}
                                    <td style="white-space: nowrap">{{ $payment->transaction_method==1?'Cash':"Cheque" }}</td>
                                    <td style="white-space: nowrap">{{ number_format($payment->amount ,2) }}</td>
                                    <td style="white-space: nowrap">{{ $payment->note }}</td>
                                    <td style="white-space: nowrap">
                                            <span class="badge badge-warning" style="font-size: 14px">Pending</span>
                                    </td>
                                    <td style="white-space: nowrap">
                                        @if ($payment->status == 1)
                                            <a class="btn btn-info btn-sm" href="{{ route('sale_receipt.payment_details', $payment->id) }}"> Vouchar </a>
                                            <a class="btn btn-success btn-sm btn-accept" role="button" data-id="{{$payment->id}}">Approve</a>
{{--                                            @if(Auth::user()->company_branch_id==0)--}}
                                            <a class="btn btn-danger btn-sm btn-delete" role="button" data-id="{{$payment->id}}">Delete</a>
{{--                                            @endif--}}
                                        @else
                                            <a class="btn btn-info btn-sm" href="{{ route('sale_receipt.payment_details', $payment->id) }}"> Vouchar </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <p>{!! $payments->render() !!}</p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>

                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modalBtnDelete">Delete</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-pay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Payment Information</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-form" enctype="multipart/form-data" name="modal-form">
                        <input type="hidden" name="payment_id" id="payment_id">
                        <div class="form-group row">
                            <label>Name</label>
                            <input class="form-control" id="modal-name" disabled>
                        </div>
                        <div class="form-group row">
                            <label>Bank Name</label>
                            <input class="form-control" id="modal-bank-name" disabled>
                        </div>
                        <div class="form-group row">
                            <label>Bank Cheque No</label>
                            <input class="form-control" id="modal-cheque-no" disabled>
                        </div>
                        <div class="form-group row">
                            <label>Bank Cheque Date</label>
                            <input class="form-control" id="modal-cheque-date" disabled>
                        </div>
                        <div class="form-group row">
                            <label>Cheque Amount</label>
                            <input class="form-control" id="modal-cheque-amount" disabled>
                        </div>
                        <div class="form-group row">
                            <label>Payment Type</label>
                            <select class="form-control select2" id="modal-pay-type" name="payment_type">
                                <option value="1">Cash</option>
                                <option value="2">Bank</option>
                            </select>
                        </div>

                        <div id="modal-bank-info">
                            <div class="form-group row">
                                <label>Storage Bank</label>
                                <select class="form-control select2 modal-bank" name="bank">
                                    <option value="">Select Bank</option>

                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label>Storage Branch</label>
                                <select class="form-control select2 modal-branch" name="branch">
                                    <option value="">Select Branch</option>
                                </select>
                            </div>

                            <div class="form-group row">
                                <label>Storage Account</label>
                                <select class="form-control select2 modal-account" name="account">
                                    <option value="">Select Account</option>
                                </select>
                            </div>

                            <div class="form-group row">
                                <label>Storage Cheque No.</label>
                                <input class="form-control" type="text" name="cheque_no" placeholder="Enter Cheque No.">
                            </div>

                            <div class="form-group row">
                                <label>Cheque Image</label>
                                <input class="form-control" name="cheque_image" type="file">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label>Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="date" name="date"
                                       value="{{ date('Y-m-d') }}" autocomplete="off">
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
                    <button type="button" class="btn btn-primary" id="modal-btn-approved">Approved</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        var due;
        $(function () {
            //Date picker
            $('#date, #next-payment-date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            var salePaymentId;

            $('body').on('click', '.btn-delete', function () {
                $('#modal-delete').modal('show');
                salePaymentId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function (e) {
                e.preventDefault();
                $.ajax({
                    method: "Post",
                    url: "{{ route('pending_cheque.delete') }}",
                    data: { salePaymentId: salePaymentId }
                }).done(function( response ) {
                    //location.reload();
                    if (response.success) {
                        Swal.fire(
                            'Successfully!',
                            response.message,
                            'success'
                        ).then((result) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        });
                    }
                });
            });

            $('body').on('click', '.btn-pending', function () {
                var paymentId = $(this).data('id');
                var clientName = $(this).data('name');
                var clientBankName = $(this).data('bank');
                var clientChequeNo = $(this).data('no');
                var ChequeDate = $(this).data('date');
                var ChequeAmount = $(this).data('amount');
                $('#modal-name').val(clientName);
                $('#modal-bank-name').val(clientBankName);
                $('#modal-cheque-no').val(clientChequeNo);
                $('#modal-cheque-amount').val(ChequeAmount);
                $('#modal-cheque-date').val(ChequeDate);
                $('#payment_id').val(paymentId);
                $('#modal-pay').modal('show');
            });
            $('body').on('click', '.btn-accept', function () {
                var paymentId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve It!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "Post",
                            url: "{{ route('client_payment.approve_cheque',['type'=>request()->get('type')]) }}",
                            data: { id: paymentId }
                        }).done(function( response ) {
                            if (response.success) {
                                Swal.fire(
                                    'Completed!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        });
                    }
                })
            });

            $('#modal-pay-type').change(function () {
                if ($(this).val() == '1') {
                    $('#modal-bank-info').hide();
                } else {
                    $('#modal-bank-info').show();
                }
            });

            $('#modal-pay-type').trigger('change');

            $('#modal-order').change(function () {
                var orderId = $(this).val();
                $('#modal-order-info').hide();

                if (orderId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_order_details') }}",
                        data: { orderId: orderId }
                    }).done(function( response ) {
                        due = parseFloat(response.due).toFixed(2);
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

            $('#modal-btn-approved').click(function () {
                var formData = new FormData($('#modal-form')[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('client_cheque.approved') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-pay').modal('hide');
                            Swal.fire(
                                'Approved!',
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
            });
        });
    </script>
@endsection
