@extends('layouts.app')

@section('title')
    Terms & Conditions
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
                <div class="card-header with-border">
                    <h3 class="card-title"> Terms & Condition information </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('terms_condition') }}">
                    @csrf

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('payment_text') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Payment Text </label>

                            <div class="col-sm-10">
                                <textarea name="payment_text" rows="2" class="form-control">{{ old('payment_text', $terms_condition->payment_text??'') }}</textarea>
                                @error('payment_text')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('delevery_duration') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Delevery Duration </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Delevery Duration"
                                    name="delevery_duration" value="{{ old('delevery_duration', $terms_condition->delevery_duration??'') }}">
                                @error('delevery_duration')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('quotation_validity') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Quotation Validity </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Quotation Validity"
                                    name="quotation_validity" value="{{ old('quotation_validity', $terms_condition->quotation_validity??'') }}">
                                @error('quotation_validity')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="form-group row {{ $errors->has('vat') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Vat </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Vat"
                                    name="vat" value="{{ old('vat', $terms_condition->vat??'') }}">
                                @error('vat')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="form-group row {{ $errors->has('tax') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Tax &  Vat</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Tax"
                                    name="tax" value="{{ old('tax', $terms_condition->tax??'') }}">
                                @error('tax')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('warranty') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Warranty </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Warranty"
                                    name="warranty" value="{{ old('warranty', $terms_condition->warranty??'') }}">
                                @error('warranty')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"> Save </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
