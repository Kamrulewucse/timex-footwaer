@extends('layouts.app')

@section('title')
    Account Head Sub Type Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Account Head Sub Type Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('account_head.sub_type.add') }}">
                    @csrf

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('type') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Type *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="type" id="type">
                                    <option value="">Select Type</option>
                                    <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Income</option>
                                    <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Expense</option>
                                </select>

                                @error('type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('account_head_type') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Account Head Type *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="account_head_type" id="account_head_type">
                                    <option value="">Select Head Type</option>
                                </select>

                                @error('account_head_type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('status') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Status *</label>

                            <div class="col-sm-10">

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ old('status') == '1' ? 'checked' : '' }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ old('status') == '0' ? 'checked' : '' }}>
                                        Inactive
                                    </label>
                                </div>

                                @error('status')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            var accountHeadTypeSelected = '{{ old('account_head_type') }}';

            $('#type').change(function () {
                var type = $(this).val();

                $('#account_head_type').html('<option value="">Select Head Type</option>');

                if (type != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_account_head_type') }}",
                        data: { type: type }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (accountHeadTypeSelected == item.id)
                                $('#account_head_type').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#account_head_type').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('#account_head_type').trigger('change');
                    });
                }
            });

            $('#type').trigger('change');
        });
    </script>
@endsection
