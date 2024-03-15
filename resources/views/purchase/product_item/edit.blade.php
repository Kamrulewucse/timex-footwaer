@extends('layouts.app')

@section('title')
    Product Model Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Product Model Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('product_item.edit', ['productItem' => $productItem->id]) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $productItem->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('supplier') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label"> Supplier *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="supplier">
                                    <option value=""> Select Supplier </option>

                                    @foreach(App\Model\Supplier::where('status',1)->get() as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier',$productItem->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>

                                @error('supplier')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('unit') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Product Unit</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="unit">
                                    <option value=""> Select Unit </option>

                                    @foreach(App\Model\Unit::where('status',1)->get() as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit', $productItem->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                    @endforeach
                                </select>

                                @error('unit')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

{{--                        <div class="form-group row {{ $errors->has('description') ? 'has-error' :'' }}">--}}
{{--                            <label class="col-sm-2 col-form-label"> Description </label>--}}

{{--                            <div class="col-sm-10">--}}
{{--                                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $productItem->description) }}</textarea>--}}

{{--                                @error('description')--}}
{{--                                <span class="help-block">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group row {{ $errors->has('image') ? 'has-error' :'' }}">--}}
{{--                            <label class="col-sm-2 col-form-label"> Product Image </label>--}}

{{--                            <div class="col-sm-10">--}}
{{--                                <input class="form-control" name="image" type="file">--}}

{{--                                @error('image')--}}
{{--                                <span class="help-block">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="form-group row {{ $errors->has('status') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Status *</label>

                            <div class="col-sm-10">

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($productItem->status == '1' ? 'checked' : '')) :
                                            (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($productItem->status == '0' ? 'checked' : '')) :
                                            (old('status') == '0' ? 'checked' : '') }}>
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
