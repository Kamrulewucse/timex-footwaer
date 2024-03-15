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
                <div class="card-body">
                    <form action="{{ route('report.purchase') }}">
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
                                    <label>Supplier</label>

                                    <select class="form-control select2" name="supplier">
                                        <option value="">All Supplier</option>

                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request()->get('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Purchase ID</label>

                                    <input type="text" class="form-control" name="purchaseId" value="{{ request()->get('purchaseId') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label> Product Item </label>


                                    <select name="product_item" class="form-control select2" id="product_item">
                                        <option value="">All Product Item</option>
                                        @foreach($productitems as $item)
                                            <option value="{{$item->id}}" {{ $item->id == request()->get('product_item') ? 'selected' : '' }}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label> Product</label>

                                    <select name="product" class="form-control select2" id="product">
                                        <option value="">All Product</option>
                                    </select>
                                    <!-- /.input group -->
                                </div>
                            </div>

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
            <section class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order No.</th>
                                <th>Supplier</th>
                                <th>Product Details</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            @php
                                $total_quantity=0;
                            @endphp

                            <tbody>

                            @foreach($orders as $order)
                                @php
                                    $total_quantity+=$order->quantity();
                                @endphp

                                <tr>
                                    <td>{{ $order->date->format('j F, Y') }}</td>
                                    <td>{{ $order->order_no }}</td>
                                    <td>{{ $order->supplier->name }}</td>
                                    <td>{{ $order->product_name }}</td>
                                    <td>{{ $order->quantity() }}</td>
                                    <td>{{ number_format($order->total, 2) }}</td>
                                    <td>{{ number_format($order->paid, 2) }}</td>
                                    <td>{{ number_format($order->due, 2) }}</td>
                                    <td><a href="{{ route('purchase_receipt.details', ['order' => $order->id]) }}">View Invoice</a></td>
                                </tr>
                            @endforeach
                            </tbody>

                            <tfoot>
                            <tr>

{{--                                <th colspan="4" class="text-right">Total</th>--}}
{{--                                <td>{{ number_format($total_quantity, 2) }}</td>--}}
{{--                                <td>{{ number_format($orders->sum('total'), 2) }}</td>--}}
{{--                                <td>{{ number_format($orders->sum('paid'), 2) }}</td>--}}
{{--                                <td>{{ number_format($orders->sum('due'), 2) }}</td>--}}
{{--                                <td></td> --}}

                                <th colspan="4" class="text-right">Total</th>
                                <td>{{ number_format($totalQuantity, 2) }}</td>
                                <td>{{ number_format($orderTotalAmount, 2) }}</td>
                                <td>{{ number_format($orderPaidAmount, 2) }}</td>
                                <td>{{ number_format($orderDueAmount, 2) }}</td>
                                <td></td>

                            </tr>
                            </tfoot>
                        </table>

                        {{ $orders->appends($appends)->links() }}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('script')
    <!-- date-range-picker -->

    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            var selectedProduct = '{{ request()->get('product') }}';
            $('#table').DataTable();
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
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

    </script>
@endsection
