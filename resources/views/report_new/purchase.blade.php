@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
  Purchase Report
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
                    <form action="{{ route('report_total_sale') }}">
                        <div class="row">
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
                                    <label>Supplier</label>

                                    <select class="form-control select2" name="supplier" required>
                                        <option value="">Select Supplier </option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request()->get('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
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
    @isset($dateWisePurchases)
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
                                    <h5 style="margin-bottom: 0px;margin-top: 0px;padding: 0">Purchase @if(request()->get('start')), Date:
                                        {{ date('d-m-Y',strtotime(request()->get('start'))) }} to {{ date('d-m-Y',strtotime(request()->get('end'))) }} @endif</h5>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">Voucher No.</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">Name</th>
                                <th>Paid</th>
                                <th class="text-center">Discount</th>
                                <th class="text-center">Due</th>
                                <th class="text-center">Total Price</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                                $totalPrice = 0;
                            ?>

                            @foreach($dateWisePurchases as $dateWisePurchase)
                                <tr>
                                    <td class="text-center">Date</td>
                                    <td colspan="6">{{ date('d-m-Y',strtotime($dateWisePurchase->date)) }}</td>
                                </tr>
                                @php
                                   $orders = $dateWisePurchase->purchase($dateWisePurchase->date);
                                @endphp
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="text-center">{{$order->order_no}}</td>
                                        <td class="text-center">{{$order->supplier->id_no??''}}</td>
                                        <td class="text-center">{{$order->supplier->name??''}}</td>
                                        <td>{{$order->paid}}</td>
                                        <td class="text-right">{{$order->discount}}</td>
                                        <td class="text-right">Tk  {{number_format($order->due,2)}}</td>
                                        <td class="text-right">Tk  {{number_format($order->total,2)}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center">Daily</td>
                                    <td colspan="2">{{$dateWisePurchase->totalDaySale}}</td>
                                    <td>{{$dateWisePurchase->totalPaid}}</td>
                                    <td class="text-right">{{$dateWisePurchase->totalDiscount}}</td>
                                    <td class="text-right">Tk  {{number_format($dateWisePurchase->totalDue,2)}}</td>
                                    <td class="text-right">Tk  {{number_format($dateWisePurchase->grandTotal,2)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
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
