@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
  Stock Report
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
                    <form action="{{ route('report_stock') }}">
                        <div class="row">

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
    @isset($inventories)
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
                                    <h4 style="margin-bottom: 0px;margin-top: 0px;padding: 0">Stock Report</h4>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th>Product Details</th>
{{--                                <th>Category Item</th>--}}
{{--                                <th>Warehouse</th>--}}
                                <th class="text-center">Average Price (TK.)</th>
                                <th class="text-center">Stock (PCS)</th>
                                <th class="text-center">Total Price (TK.)</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                                $totalPrice = 0;
                            ?>

                            @foreach($inventories as $inventory)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td>{{$inventory->productItem->name??''}}</td>
{{--                                    <td>{{$inventory->productCategory->name??''}}</td>--}}
{{--                                    <td>{{$inventory->warehouse->name??''}}</td>--}}
                                    <td class="text-right">Tk  {{number_format($inventory->avg_unit_price,2)}}</td>
                                    <td class="text-right">{{$inventory->quantity}}</td>

                                    <td class="text-right">Tk  {{number_format($inventory->quantity*$inventory->unit_price,2)}}</td>
                                </tr>

                                <?php
                                    $totalPrice += $inventory->quantity * $inventory->unit_price;
                                ?>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2" style="border-right: 1px solid #fff !important;"></th>
                                <th style="border-right: 1px solid #fff !important;border-left: 1px solid #fff !important;">Group Wise Total </th>
                                <th class="text-right">{{number_format($inventories->sum('quantity',2))}}</th>
                                <th colspan="3" class="text-right">Tk  {{number_format($totalPrice, 2)}}</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="border-right: 1px solid #fff !important;"></th>
                                <th style="border-right: 1px solid #fff !important;border-left: 1px solid #fff !important;">Brand Wise Total </th>
                                <th class="text-right">{{number_format($inventories->sum('quantity',2))}}</th>
                                <th colspan="3" class="text-right">Tk  {{number_format($totalPrice, 2)}}</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="border-right: 1px solid #fff !important;"></th>
                                <th style="border-right: 1px solid #fff !important;border-left: 1px solid #fff !important;">Grand Total </th>
                                <th class="text-right">{{number_format($inventories->sum('quantity',2))}}</th>
                                <th colspan="3" class="text-right">Tk  {{number_format($totalPrice, 2)}}</th>
                            </tr>
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
