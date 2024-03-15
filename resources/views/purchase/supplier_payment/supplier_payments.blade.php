@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Payments of {{ $supplier->name }}
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
                    <table id="table" class="table table-bordered table-striped" style="width: 100% !important">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Payment Type</th>
                            <th>Note</th>
                            <th>Payment by</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->date->format('Y-m-d') }}</td>
                                <td>{{ $payment->supplier->name??'' }}</td>

                                <td>
                                    @if($payment->transaction_method == 5)
                                        Purchase Adjustment Discount
                                    @elseif($payment->transaction_method == 6)
                                        Purchase Return Adjustment
                                    @elseif($payment->transaction_method == 1)
                                        Cash
                                    @elseif($payment->transaction_method == 3)
                                        Mobile Banking
                                    @elseif($payment->transaction_method == 4)
                                        Account Adjustment
                                    @else
                                        Bank
{{--                                    {{ ($payment->transaction_method==1?'Cash':($payment->transaction_method == 4?'Account Adjustment':'Bank')) }}--}}
                                    @endif
                                </td>
                                <td>{{ number_format($payment->amount,2) }}</td>
                                <td>
                                    @if ($payment->purchase_order_id == null)
                                        <span class="label label-success">Due Payment</span>
                                    @else
                                        <span class="label label-info">Invoice Payment</span>
                                    @endif
                                </td>
                                <td>{{ $payment->note }}</td>
                                <td>{{ $payment->user->name ?? $payment->user_id==0?'Level':'' }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('purchase_receipt.payment_details', $payment->id) }}"> Voucher </a>
                                    @if(Auth::user()->role == '1' && Auth::user()->company_branch_id==0)
                                        <a class="btn btn-primary btn-sm btn-edit" role="button" data-id="{{$payment->id}}" data-customer-id="{{$payment->customer_id}}" data-amount="{{$payment->amount}}">Edit</a>
{{--                                        <a class="btn btn-danger btn-sm btn-delete" role="button" data-id="{{$payment->id}}" data-customer-id="{{$payment->customer_id}}">Delete</a>--}}
                                    @endif
                                </td>
                            </tr>
                            <!--- edit modal--->
                            <div class="modal fade" id="modal-edit">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Payment Information Edit</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>

                                        </div>
                                        <div class="modal-body">
                                            <form id="modal-form-edit" enctype="multipart/form-data" name="modal-form-edit">
                                                <div class="form-group row">
                                                    <input type="hidden" name="payment_id" id="payment_id_edit">
                                                    <label>Payment Type</label>
                                                    <select class="form-control select2" id="modal-pay-type" name="payment_type" disabled>
                                                        @if($payment->transaction_method ==1)
                                                            <option value="1" selected>Cash</option>
                                                        @elseif($payment->transaction_method ==2)
                                                            <option value="2" selected>Bank</option>
                                                        @else
                                                            <option value="3" selected>Mobile Banking</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="form-group row">
                                                    <label>Amount</label>
                                                    <input class="form-control"   name="amount" id="amount-edit" placeholder="Enter Amount">
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
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!---end edit model--->
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                <p>{!! $payments->render() !!}</p>
            </div>
        </div>
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
    <!--- delete model--->
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
    <!-- /.delete modal -->
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

            $('body').on('click', '.btn-pending', function () {
                var paymentId = $(this).data('id');
                var clientName = $(this).data('name');
                $('#modal-name').val(clientName);
                $('#payment_id').val(paymentId);
                $('#modal-pay').modal('show');
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

            // edit supplier voucher
            $('body').on('click', '.btn-edit', function () {
                var paymentId = $(this).data('id');
                var amountEdit = $(this).data('amount');
                $('#payment_id_edit').val(paymentId);
                $('#amount-edit').val(amountEdit);
                $('#modal-edit').modal('show');

            });

            $('#modal-btn-pay').click(function () {
                var formData = new FormData($('#modal-form-edit')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('supplier_payment.voucher_update') }}",
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
                                location.reload();
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

            // supplier vouchar delete
            $('body').on('click', '.btn-delete', function () {
                $('#modal-delete').modal('show');
                paymentId = $(this).data('id');
                customer_id = $(this).data('customer-id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('suppler_payment_voucher.delete') }}",
                    data: { id: paymentId,customer_id:customer_id }
                }).done(function( msg ) {
                    location.reload();
                });
            });

        });
    </script>
@endsection
