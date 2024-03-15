@extends('layouts.app')

@section('title')
    Branch Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Branch Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('branch_cash_edit',['branchCash'=>$branchCash->id]) }}">
                    @csrf

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('branch') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Branch *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="branch">
                                    <option value="{{ $branch->id }}" {{ old('branch',$branchCash->id) == $branch->id ? 'selected' : '' }}>{{ $branch->name?? '' }}</option>
                                </select>

                                @error('branch')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('opening_balance') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Opening Balance *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Opening Balance"
                                       name="opening_balance" value="{{ old('opening_balance',$branchCash->opening_balance) }}">
                                @error('opening_balance')
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
