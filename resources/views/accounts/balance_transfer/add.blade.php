@extends('layouts.app')
@section('title')
    Balance Transfer
@endsection
@section('style')
    <style>
        .form-control {
            height: calc(1.9rem + 2px);
            font-size: .8rem;
            border-radius: 0;
        }
        .select2-container--default .select2-selection--single {
            height: calc(1.9rem + 2px);
            font-size: .8rem;
            border-radius: 0;
            padding: 0.26875rem 0.75rem;
        }
        .form-control::placeholder {
            color: #000000;
            opacity: 1; /* Firefox */
        }

        .form-control:-ms-input-placeholder { /* Internet Explorer 10-11 */
            color: #000000;
        }

        .form-control::-ms-input-placeholder { /* Microsoft Edge */
            color: #000000;
        }
        .form-group {
            margin-bottom: 0.6rem;
        }

        legend {
            font-size: 1.4rem;
            font-weight: bold;
        }

        .card-title {
            font-size: 1.5rem;
        }
        .table-bordered thead td, .table-bordered thead th {
            white-space: nowrap;
            font-size: 14px;
        }
        .table td, .table th {
            padding: 5px;
        }
        .table td .form-group{
            margin-bottom: 0!important;
        }
        label:not(.form-check-label):not(.custom-file-label) {
            font-size: 14px;
        }
        .table td, .table th {
            vertical-align: middle;
        }

        .table td, .table th {
            text-align: center;
        }
        .table td .form-group input{
            text-align: center;

        }
        .table.other th,.table.other td{
            text-align: left;
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-outline card-default">
                <!-- /.card-header -->
                <form enctype="multipart/form-data" action="{{route('balance_transfer.add')}}" class="form-horizontal" method="post" id="transfer_form">
                    @csrf
                    <div class="card-body">
                        <div class="receipt-payment-item">
                            <div class="row">

                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Source Information:</legend>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group row {{ $errors->has('source_branch') ? 'has-error' :'' }}">
                                                    <label>Source Branch *</label>
                                                    <div class="col-sm-12">
                                                        <select class="form-control select2 source_branch" name="source_branch" id="source_branch_id">
                                                            <option value="">Select Branch</option>
                                                            @if(auth()->user()->company_branch_id == 0)
                                                                <option value="0" {{ old('source_branch') == '0' ? 'selected' : '' }}>Admin Cash/Bank</option>
                                                            @endif
                                                            @foreach($companyBranches as $companyBranch)
                                                                <option value="{{ $companyBranch->id }}" {{ old('source_branch',auth()->user()->company_branch_id) == $companyBranch->id ? 'selected' : '' }}>{{ $companyBranch->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('source_branch')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group row {{ $errors->has('type') ? 'has-error' :'' }}">
                                                    <label>Type</label>
                                                    <div class="col-sm-12">
                                                        <select class="form-control select2" name="type" id="type">
                                                            <option value="">Select Type</option>
                                                            <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Bank To Cash</option>
                                                            <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Cash To Bank</option>
                                                            <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Bank To Bank</option>
                                                            <option value="4" {{ old('type') == '4' ? 'selected' : '' }}>Cash To Cash</option>
                                                        </select>

                                                        @error('type')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="source-bank-info">
                                                <div class="form-group row {{ $errors->has('source_account') ? 'has-error' :'' }}">
                                                    <label>Source Account *</label>

                                                    <div class="col-sm-12">
                                                        <select class="form-control select2" name="source_account" id="source_account">
                                                            <option value="">Select Account</option>
                                                        </select>

                                                        @error('source_account')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('source_cheque_no') ? 'has-error' :'' }}">
                                                    <label>Source Cheque No</label>

                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" name="source_cheque_no" placeholder="Enter Cheque No.">

                                                        @error('source_cheque_no')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('source_cheque_image') ? 'has-error' :'' }}">
                                                    <label>Source Cheque Image</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control" name="source_cheque_image">
                                                        @error('source_cheque_image')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group row {{ $errors->has('amount') ? 'has-error' :'' }}">
                                                    <label>Amount *</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" name="amount" placeholder="Enter Amount" value="{{ old('amount') }}">
                                                        @error('amount')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('date') ? 'has-error' :'' }}">
                                                    <label>Date *</label>
                                                    <div class="col-sm-12">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
                                                        </div>
                                                        <!-- /.input group -->

                                                        @error('date')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('note') ? 'has-error' :'' }}">
                                                    <label>Note</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" name="note" placeholder="Enter Note" value="{{ old('note') }}">
                                                        @error('note')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Destination Information:</legend>
                                        <div class="row">
                                            <div class="col-sm-12" id="target_branch_hide">
                                                <div class="form-group row {{ $errors->has('target_branch') ? 'has-error' :'' }}">
                                                    <label>Target Branch *</label>
                                                    <div class="col-sm-12">
                                                        <select class="form-control select2 target_branch" name="target_branch">
                                                            <option value="">Select Branch</option>
                                                            <option value="0" {{ old('target_branch') == '0' ? 'selected' : '' }}>Admin Cash</option>
                                                            @foreach($targetBranches as $companyBranch)
                                                                <option value="{{ $companyBranch->id }}" {{ old('target_branch') == $companyBranch->id ? 'selected' : '' }}>{{ $companyBranch->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('target_branch')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="target-bank-info">
                                                <div class="form-group row {{ $errors->has('target_account') ? 'has-error' :'' }}">
                                                    <label>Target Account *</label>
                                                    <div class="col-sm-12">
                                                        <select class="form-control select2" name="target_account" id="target_account">
                                                            <option value="">Select Account</option>
                                                            @foreach($bankAccounts as $bankAccount)
                                                                <option value="{{ $bankAccount->id }}" {{ old('target_account') == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->account_no }}</option>
                                                            @endforeach
                                                        </select>
                                                        </select>
                                                        @error('target_account')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('target_cheque_no') ? 'has-error' :'' }}">
                                                    <label>Target Cheque No</label>

                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" name="target_cheque_no" placeholder="Enter Cheque No.">

                                                        @error('target_cheque_no')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('target_cheque_image') ? 'has-error' :'' }}">
                                                    <label>Target Cheque Image</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control" name="target_cheque_image">

                                                        @error('target_cheque_image')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button id="btn-save" type="submit" class="btn btn-success bg-gradient-success submission">Save</button>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
            <!-- /.card -->
        </div>
        <!--/.col (left) -->
    </div>

@endsection
@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            var sourceBranchSelected = '{{ old('source_branch') }}';
            var sourceAccountSelected = '{{ old('source_account') }}';
            var targetBranchSelected = '{{ old('target_branch') }}';

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('#source_branch_id').change(function () {
                var branch_id = $(this).val();
                $('#type').val('');
                $('#type option').prop('disabled', false);

                if (branch_id != '0') {
                    $('#type option[value="1"]').prop('disabled', true);
                    $('#type option[value="3"]').prop('disabled', true);
                }

                $('#type').trigger('change');
            });


            $('#type').change(function () {
                var type = $(this).val();

                $('#account_head_type').html('<option value="">Select Account Head Type</option>');
                $('#account_head_sub_type').html('<option value="">Select Account Head Sub Type</option>');

                if (type != '') {
                    if (type == '1') {
                        $('#source-bank-info').show();
                        $('#target-bank-info').hide();
                        $('#target_branch_hide').show();
                    } else if (type == '2') {
                        $('#source-bank-info').hide();
                        $('#target-bank-info').show();
                        $('#target_branch_hide').hide();
                    } else if(type == '3'){
                        $('#source-bank-info').show();
                        $('#target-bank-info').show();
                        $('#target_branch_hide').hide();
                    }else{
                        $('#source-bank-info').hide();
                        $('#target-bank-info').hide();
                        $('#target_branch_hide').show();
                    }
                } else {
                    $('#source-bank-info').hide();
                    $('#target-bank-info').hide();
                }
            });

            $('#type').trigger('change');

            $('.source_branch').change(function () {
                var branchId = $(this).val();
                $('#source_account').html('<option value="">Select Account</option>');

                if (branchId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_bank_account') }}",
                        data: { branchId: branchId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (sourceAccountSelected == item.id)
                                $('#source_account').append('<option value="'+item.id+'" selected>'+item.account_no+'</option>');
                            else
                                $('#source_account').append('<option value="'+item.id+'">'+item.account_no+'</option>');
                        });
                    });
                }
            });

            {{--$('.target_branch').change(function () {--}}
            {{--    var branchId = $(this).val();--}}
            {{--    $('#target_account').html('<option value="">Select Account</option>');--}}

            {{--    if (branchId != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('get_bank_account') }}",--}}
            {{--            data: { branchId: branchId }--}}
            {{--        }).done(function( response ) {--}}
            {{--            $.each(response, function( index, item ) {--}}
            {{--                if (targetAccountSelected == item.id)--}}
            {{--                    $('#target_account').append('<option value="'+item.id+'" selected>'+item.account_no+'</option>');--}}
            {{--                else--}}
            {{--                    $('#target_account').append('<option value="'+item.id+'">'+item.account_no+'</option>');--}}
            {{--            });--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}
        });
        $(function () {
            $('body').on('click', '.submission', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Want to transfer balance",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes,Transfer the balance!'

                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#transfer_form').submit();
                    }
                })

            });
        });
    </script>
@endsection
