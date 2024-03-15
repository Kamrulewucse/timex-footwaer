@extends('layouts.app')

@section('title')
    Product Serial Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Product Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{ route('product.add') }}">
                    @csrf

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('product_item') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Product Model *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="product_item">
                                    <option value="">Select Product Model</option>

                                    @foreach($productItems as $productItem)
                                        <option value="{{ $productItem->id }}" {{ old('product_item') == $productItem->id ? 'selected' : '' }}>{{ $productItem->name }}</option>
                                    @endforeach
                                </select>

                                @error('product_item')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Product Serial *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter serial"
                                       name="name" value="{{ old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="form-group row {{ $errors->has('code') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Code</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Code"
                                       name="code" value="{{ old('code') }}">

                                @error('code')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('image') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Image</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control"
                                       name="image">

                                @error('image')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('description') ? 'has-error' :'' }}">
                            <label class="col-sm-2 col-form-label">Description</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Code"
                                       name="description" value="{{ old('description') }}">

                                @error('description')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}

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
