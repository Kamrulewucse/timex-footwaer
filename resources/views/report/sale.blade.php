@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Sale Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('report.sale') }}">
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
                                    <!-- /.input group -->
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
                                    <!-- /.input group -->
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Customer</label>

                                    <select class="form-control select2" name="customer">
                                        <option value="">All Customer</option>

                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ request()->get('customer') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label> Report type </label>
                                    <select class="form-control select2" name="report_type">
                                        <option value="">All Item</option>
                                        <option @if (request()->get('report_type')==1) selected @endif value="1"> Due</option>
                                        <option @if (request()->get('report_type')==2) selected @endif value="2"> Paid</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row" style="margin-right: 1px !important">
                                    <label>Order No.</label>
                                    <input type="text" class="form-control" name="order_no" value="{{ request()->get('order_no') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label> Product Item </label>


                                    <select name="product_item" class="form-control select2" id="product_item">
                                        <option value="">All Product Item</option>
                                        @foreach($product_items as $item)
                                            <option value="{{$item->id}}" {{ $item->id == request()->get('product_item') ? 'selected' : '' }}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            @if ($branches)
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label>Company Branch </label>
                                        <select name="company_branch" class="form-control select2" id="company_branch">
                                            <option value="">All Company Branch</option>
                                            @foreach($branches as $branch)
                                                <option value="{{$branch->id}}" {{ $branch->id == request()->get('company_branch') ? 'selected' : '' }}>{{$branch->name}}</option>
                                            @endforeach
                                        </select>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                            @endif
                            {{--                            <div class="col-md-3">--}}
                            {{--                                <div class="form-group row">--}}
                            {{--                                    <label> Product</label>--}}

                            {{--                                    <select name="product" class="form-control" id="product">--}}
                            {{--                                        <option value="">All Product</option>--}}
                            {{--                                    </select>--}}
                            {{--                                    <!-- /.input group -->--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}


                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>	&nbsp;</label>
                                    <input class="btn btn-primary form-control" type="submit" value="Search">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>
                    <div id="prinarea">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order No.</th>
                                    <th>Name</th>
                                    <th>Product Details</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Previous Due</th>
                                    <th>Return Amount</th>
                                    <th>Discount</th>
                                    <th>Transport Cost</th>
                                    <th>Sale Adjustment</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->date->format('j F, Y') }}</td>
                                        <td>{{ $order->order_no }}</td>
                                        <td>{{ $order->customer->name ?? '' }}</td>
                                        <td>{{ $order->product_name  }}</td>
                                        <td>{{ $order->quantity() }}</td>
                                        <td>{{ number_format($order->sub_total, 2) }}</td>
                                        <td>{{ number_format($order->previous_due ?? '0', 2) }}</td>
                                        <td>{{ number_format($order->return_amount ?? '0', 2) }}</td>
                                        <td>{{ number_format($order->discount ?? '0', 2) }}</td>
                                        <td>{{ number_format($order->transport_cost ?? '0', 2) }}</td>
                                        <td>{{ number_format($order->sale_adjustment ?? '0', 2) }}</td>
                                        <td>{{ number_format($order->paid, 2) }}</td>
                                        <td>{{ number_format(($order->current_due), 2) }}</td>
                                        <td><a href="{{ route('sale_receipt.details', ['order' => $order->id]) }}">View Invoice</a></td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <td>{{ number_format($totalQuantity, 2) }}</td>
                                    <td>{{ number_format($orderTotalAmount, 2) }}</td>
                                    <td>{{ number_format($totalPreviousDue, 2) }}</td>
                                    <td>{{ number_format($totalReturnAmount, 2) }}</td>
                                    <td>{{ number_format($totalDiscount, 2) }}</td>
                                    <td>{{ number_format($totalTransportCost, 2) }}</td>
                                    <td>{{ number_format($totalSaleAdjustment, 2) }}</td>
                                    <td>{{ number_format($orderPaidAmount, 2) }}</td>
                                    <td>{{ number_format($orderDueAmount, 2) }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            $('#table').DataTable();
            var selectedProduct = '{{ request()->get('product') }}';
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('#type').change(function () {
                if ($(this).val() == '') {
                    $('#customer').hide();
                    $('#supplier').hide();
                }else if ($(this).val() == 1) {
                    $('#customer').show();
                    $('#supplier').hide();
                } else {
                    $('#customer').hide();
                    $('#supplier').show();
                }
            });

            $('#type').trigger('change');

            $('#product_item').change(function() {
                var productItemId = $(this).val();
                $('#product').html('<option value="">All Product</option>');

                if (productItemId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_products') }}",
                        data: {productItemId: productItemId}
                    }).done(function (response) {
                        $.each(response, function( index, item ) {
                            if (selectedProduct == item.id)
                                $('#product').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#product').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });

            $('#product_item').trigger('change');
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
