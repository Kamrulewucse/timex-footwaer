@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Bank Account
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
                    <a class="btn btn-primary" href="{{ route('bank_account.add') }}">Add Bank Account</a>

                    <hr>
                    <div class="table-responsive">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Account Name</th>
                            <th>Account No.</th>
                            <th>Bank</th>
                            <th>Branch</th>
                            <th>Description</th>
                            <th>Opening Balance</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($accounts as $account)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td>{{ $account->account_no }}</td>
                                <td>{{ $account->bank->name }}</td>
                                <td>{{ $account->branch->name }}</td>
                                <td>{{ $account->description }}</td>
                                <td>Tk {{ number_format($account->opening_balance, 2) }}</td>
                                <td>Tk {{ number_format($account->balance, 2) }}</td>
                                <td>
                                    @if ($account->status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('bank_account.edit', ['account' => $account->id]) }}">Edit</a>
                                    &nbsp;
                                    @if(auth()->user()->role==0)
                                    <a role="button" data-id="{{$account->id}}" class="btn btn-warning btn-sm btn-withdraw">Withdraw</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="modal-withdraw">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Withdraw Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <form id="modal-form" enctype="multipart/form-data" name="modal-form">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group row">
                        <label>Withdraw Amount</label>
                        <input class="form-control" name="amount" placeholder="Enter Amount" id="amount">
                    </div>

                    <div class="form-group row">
                        <label>Note</label>
                        <input class="form-control" name="note" placeholder="Enter Note" id="note">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modal-btn-withdraw">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#table').DataTable();
            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('body').on('click', '.btn-withdraw', function () {
                var accountId = $(this).data('id');

                $.ajax({
                    method: "GET",
                    url: "{{ route('bank_account_details_json') }}",
                    data: { accountId: accountId }
                }).done(function( response ) {
                    $('#id').val(response.id);
                    $('#modal-withdraw').modal('show');
                });
            });

            $('#modal-btn-withdraw').click(function () {
                var formData = new FormData($('#modal-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('bank_amount_withdraw_post') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            $('#modal-withdraw').modal('hide');
                            Swal.fire(
                                'Update!',
                                response.message,
                                'success'
                            ).then((result) => {
                                location.reload();
                                //window.location.href = response.redirect_url;
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
        })
    </script>
@endsection
