@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
  Product Wise Sale Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Filter</h3>
                </div>
                <!-- /.box-header -->

                <div class="card-body">
                    <form action="{{ route('report_product_wise_sale') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Sale Type</label>
                                    <select class="form-control select2" name="sale_type" required>
                                        <option value="">Select Option</option>
                                        <option value="1" {{ request()->get('sale_type')=='1'?'selected':'' }}>Retail Sale</option>
                                        <option value="2" {{ request()->get('sale_type')=='2'?'selected':'' }}>Whole Sale</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Product Item</label>

                                    <select class="form-control select2" name="product_item" required>
                                        <option value="all">All Product Item </option>
                                        @foreach($productItems as $productItem)
                                            <option value="{{ $productItem->id }}" {{ request()->get('product_item') == $productItem->id ? 'selected' : '' }}>{{ $productItem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')??date('Y-m-d')  }}" autocomplete="off" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>End Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="end" name="end" value="{{ request()->get('end')??date('Y-m-d')  }}" autocomplete="off" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>	&nbsp;</label>
                                    <input class="btn btn-primary form-control" type="submit" value="Submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @isset($orderProducts)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><br>
                    <div id="prinarea">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                    <h2 style="margin-bottom: 0px;">Megha Footwear</h2>
                                    <h6 style="margin-bottom: 0px;">Megha trading, 174, Siddik bazar Dhaka - 1000</h6>
                                    <h6 style="margin-bottom: 0px;">Hotline: 01841509263 Phone: 02226638333, 01720009263</h6>
                                    <h6 style="margin-bottom: 0px;">Bin No: 001067154-0205</h6>
                                    <h5 style="margin-bottom: 0px;margin-top: 0px;padding: 0">Product Wise Sale @if(request()->get('start')), Date:
                                        {{ date('d-m-Y',strtotime(request()->get('start'))) }} to {{ date('d-m-Y',strtotime(request()->get('end'))) }} @endif</h5>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Product ID</th>
                                <th>Product</th>
{{--                                <th>Category Item</th>--}}
{{--                                <th>Warehouse</th>--}}
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Total Sale Price</th>
                                <th class="text-center">Net Sale Price</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                                $totalPrice = 0;
                            ?>

                            @foreach($orderProducts as $orderProduct)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-center">Shoe</td>
                                    <td class="text-center">{{$orderProduct->product_item_id}}</td>
                                    <td>{{$orderProduct->productItem->name??''}}</td>
                                    <td class="text-right">{{$orderProduct->quantity}}</td>
                                    <td class="text-right">Tk  {{number_format($orderProduct->buy_price,2)}}</td>
                                    <td class="text-right">Tk  {{number_format($orderProduct->buy_price,2)}}</td>
                                </tr>

                                <?php
//                                    $totalPrice += $inventory->quantity * $inventory->unit_price;
                                ?>
                            @endforeach
                            </tbody>
                            <tfoot>
{{--                            <tr>--}}
{{--                                <th colspan="2" style="border-right: 1px solid #fff !important;"></th>--}}
{{--                                <th style="border-right: 1px solid #fff !important;border-left: 1px solid #fff !important;">Group Wise Total </th>--}}
{{--                                <th class="text-right">{{number_format($inventories->sum('quantity',2))}}</th>--}}
{{--                                <th colspan="3" class="text-right">Tk  {{number_format($totalPrice, 2)}}</th>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th colspan="2" style="border-right: 1px solid #fff !important;"></th>--}}
{{--                                <th style="border-right: 1px solid #fff !important;border-left: 1px solid #fff !important;">Brand Wise Total </th>--}}
{{--                                <th class="text-right">{{number_format($inventories->sum('quantity',2))}}</th>--}}
{{--                                <th colspan="3" class="text-right">Tk  {{number_format($totalPrice, 2)}}</th>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th colspan="2" style="border-right: 1px solid #fff !important;"></th>--}}
{{--                                <th style="border-right: 1px solid #fff !important;border-left: 1px solid #fff !important;">Grand Total </th>--}}
{{--                                <th class="text-right">{{number_format($inventories->sum('quantity',2))}}</th>--}}
{{--                                <th colspan="3" class="text-right">Tk  {{number_format($totalPrice, 2)}}</th>--}}
{{--                            </tr>--}}
                            </tfoot>
                        </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endisset
@endsection

@section('script')
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            $('#table').DataTable();
        });
        $('#start, #end').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {
            $('#table').DataTable().destroy();
            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
