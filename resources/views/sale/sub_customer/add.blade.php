@extends('layouts.app')

@section('title')
    Sub Customer Add
@endsection
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Customer Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('sub_customer.add') }}">
                    @csrf

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('customer') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Customer *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="customer" id="customer" required>
                                    <option value=""> Select Customer </option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}" {{ old('customer') ===$customer->id  ? 'selected' : '' }}>{{$customer->name}}</option>
                                    @endforeach
                                </select>

                                @error('customer')
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

                        <div class="form-group row {{ $errors->has('mobile_no') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Mobile No. *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Mobile No."
                                       name="mobile_no" value="{{ old('mobile_no') }}">

                                @error('mobile_no')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Address</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Address"
                                       name="address" value="{{ old('address') }}">

                                @error('address')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('sub_customer_old_id') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Sub Customer Old id</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter sub_customer_old_id"
                                       name="sub_customer_old_id" value="{{ old('sub_customer_old_id') }}">

                                @error('sub_customer_old_id')
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
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            // Initialize Select2 Elements
            $('.select2').select2();
        });
    </script>
@endsection
