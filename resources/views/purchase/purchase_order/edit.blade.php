@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Purchase Order Edit
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('purchase_order.edit', ['order'=>$order->id]) }}" id="purchase_order_form">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('supplier_id') ? 'has-error' :'' }}">
                                    <label>Supplier</label>

                                    <select class="form-control select2" style="width: 100%;" name="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $order->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('supplier_id')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('warehouse_id') ? 'has-error' :'' }}">
                                    <label>Warehouse</label>

                                    <select class="form-control select2" style="width: 100%;" name="warehouse_id" data-placeholder="Select Warehouse">
                                        {{-- <option value="">Select Warehouse</option> --}}

                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $order->warehouse_id) == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="product_type" value="{{ $order->product_type }}"/>
                                    @error('warehouse')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ old('date', $order->date->format('Y-m-d')) }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Model </th>
                                        <th>Product Size </th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit Price</th>
                                        <th width="10%">Selling Price</th>
                                        <th width="10%">Wholesale Price</th>
                                        <th>Total Cost</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product_item') != null && sizeof(old('product_item')) > 0)
                                    @foreach(old('product_item') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group row {{ $errors->has('product_item.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control product_item" name="product_item[]" style="width: 100%;" value="{{ old('product_item.'.$loop->index) }}">
                                                    <input type="hidden" class="form-control" name="serial[]" value="{{ old('serial.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row {{ $errors->has('product_category.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control product_category" name="product_category[]" style="width: 100%;" value="{{ old('product_category.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}" required>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}" required>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row {{ $errors->has('selling_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control selling_price" name="selling_price[]" value="{{ old('selling_price.'.$loop->index) }}" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row {{ $errors->has('wholesale_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control wholesale_price" name="wholesale_price[]" value="{{ old('wholesale_price.'.$loop->index) }}" required>
                                                </div>
                                            </td>

                                            <td class="total-cost">Tk 0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($order->products as $index => $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" class="form-control product_item" name="product_item[]" value="{{$item->productItem->name??''}}" style="width: 100%;">
                                                    <input type="hidden" class="form-control" value="{{$item->serial??''}}" name="serial[]">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" class="form-control product_category" name="product_category[]" value="{{$item->productCategory->name??''}}" style="width: 100%;" >
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row">
                                                    <input type="number" step="any" class="form-control quantity" value="{{ $item->quantity }}" name="quantity[]" required>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ $item->unit_price }}" required>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" class="form-control selling_price" name="selling_price[]" value="{{ $item->selling_price }}" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" class="form-control wholesale_price" name="wholesale_price[]" value="{{ $item->wholesale_price }}" required>
                                                </div>
                                            </td>

                                            <td class="total-cost">Tk 0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td>
                                            <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add Product</a>
                                        </td>
                                        <th colspan="" class="text-right">Total Quantity</th>
                                        <th id="total-quantity">0</th>
                                        <th colspan="" class="text-right"></th>
                                        <th colspan="2" class="text-right">Sub Total</th>
                                        <th id="total-amount">Tk 0.00</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right"> Transport Cost </th>
                                        <td>
                                            <div class="form-group row {{ $errors->has('transport_cost') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="transport_cost" id="transport_cost" value="{{ old('transport_cost', $order->transport_cost) }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right"> Discount (%) </th>
                                        <td>
                                            <div class="form-group row {{ $errors->has('discount_percentage') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage', $order->discount_percentage) }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right">Discount</th>
                                        <td>
                                            <div class="form-group row {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="discount" id="discount" value="{{ old('discount', $order->discount) }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right">Paid</th>
                                        <td>
                                            <div class="form-group row {{ $errors->has('paid') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="paid" id="paid" value="{{ old('paid', $order->paid) }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right">Due</th>
                                        <th id="due">Tk 0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right">Total</th>
                                        <th id="total">Tk 0.00</th>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="card-footer">
                        <input type="hidden" name="total" id="total_amount">
                        <button type="submit" class="btn btn-primary submission">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <template id="template-product">
        @php
        $i=1;
        @endphp
        <tr class="product-item" >
            <td>
                <div class="form-group row">
                    <input type="text" class="form-control product_item" name="product_item[]" style="width: 100%;" >
                    <input type="hidden" class="form-control" name="serial[]">
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="text" class="form-control product_category" name="product_category[]" style="width: 100%;" >
                </div>
            </td>

            <td>
                <div class="form-group row">
                    <input type="number" step="any" class="form-control quantity" value="6" name="quantity[]" required>
                </div>
            </td>

            <td>
                <div class="form-group row">
                    <input type="text" class="form-control unit_price" name="unit_price[]" value="0" required>
                </div>
            </td>



            <td>
                <div class="form-group row">
                    <input type="text" class="form-control selling_price" value="0" name="selling_price[]">
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="text" class="form-control wholesale_price" value="0" name="wholesale_price[]">
                </div>
            </td>

            <td class="total-cost">Tk 0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            // $('.product').select2();
            $('.select2').select2();

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('.product_item').autocomplete({
                source:function (request, response) {
                    $.getJSON('{{ route("get_productItem_suggestion") }}?term='+request.term, function (data) {
                        console.log(data);
                        var array = $.map(data, function (row) {
                            if(row.type == 1) {
                                return {
                                    value: row.name,
                                    label: row.name,
                                }
                            }else {
                                return {
                                    value: row.name,
                                    label: row.name,
                                }
                            }
                        });
                        response($.ui.autocomplete.filter(array, request.term));
                    })
                },
                minLength: 2,
                //delay: 50,
            });

            $('.product_category').autocomplete({
                source:function (request, response) {
                    $.getJSON('{{ route("get_categoryItem_suggestion") }}?term='+request.term, function (data) {
                        console.log(data);
                        var array = $.map(data, function (row) {
                            if(row.type == 1) {
                                return {
                                    value: row.name,
                                    label: row.name,
                                }
                            }else {
                                return {
                                    value: row.name,
                                    label: row.name,
                                }
                            }
                        });
                        response($.ui.autocomplete.filter(array, request.term));
                    })
                },
                minLength: 2,
                //delay: 50,
            });

            var message = '{{ session('message') }}';

            if (!window.performance || window.performance.navigation.type != window.performance.navigation.TYPE_BACK_FORWARD) {
                if (message != '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                    });
                }
            }

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);


                item.find('.product_item').autocomplete({
                    source:function (request, response) {
                        $.getJSON('{{ route('get_productItem_suggestion') }}?term='+request.term, function (data) {
                            console.log(data);
                            var array = $.map(data, function (row) {
                                if(row.type == 1) {
                                    return {
                                        value: row.name,
                                        label: row.name,
                                    }
                                }else {
                                    return {
                                        value: row.name,
                                        label: row.name,
                                    }
                                }
                            });

                            response($.ui.autocomplete.filter(array, request.term));
                        })
                    },
                    minLength: 2,
                    delay: 300,
                });

                item.find('.product_category').autocomplete({
                    source:function (request, response) {
                        $.getJSON('{{ route('get_categoryItem_suggestion') }}?term='+request.term, function (data) {
                            console.log(data);
                            var array = $.map(data, function (row) {
                                if(row.type == 1) {
                                    return {
                                        value: row.name,
                                        label: row.name,
                                    }
                                }else {
                                    return {
                                        value: row.name,
                                        label: row.name,
                                    }
                                }
                            });

                            response($.ui.autocomplete.filter(array, request.term));
                        })
                    },
                    minLength: 2,
                    delay: 300,
                });

                $('#product-container').append(item);

                if ($('.product-item').length + $('.service-item').length >= 1 ) {
                    $('.btn-remove').show();
                    $('.btn-remove-service').show();
                }

            });

            // $('#btn-add-product').click(function () {
            //     var html = $('#template-product').html();
            //     var item = $(html);
            //
            //     // item.find('.serial').val('CGSP' + Math.floor((Math.random() * 100000)));
            //     $('#product-container').append(item);
            //     initProduct();
            //     $('.select2').select2();
            //     if ($('.product-item').length >= 1 ) {
            //         $('.btn-remove').show();
            //     }
            // });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item').length <= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('keyup', '#discount', function () {
                $('#discount_percentage').val(0);
            });
            $('body').on('change', '.quantity,#paid, #transport_cost, #discount,#discount_percentage, .unit_price', function () {
                calculate();
            });
            $('body').on('keyup', '.quantity,#paid, #transport_cost, #discount,#discount_percentage, .unit_price', function () {
                calculate();
            });

            if ($('.product-item').length <= 1 ) {
                $('.btn-remove').show();
            } else {
                $('.btn-remove').show();
            }

            initProduct();
            calculate();

            var timer = null;
            $('body').on('keyup','.product_item','.product_category', function(){
                clearTimeout(timer);
                timer = setTimeout(getPrice,6000);
            })
        });

        function getPrice() {
            $('.product-item').each(function(i, obj) {
                var product_item = $('.product_item:eq('+i+')').val();
                var product_category = $('.product_category:eq('+i+')').val();

                if ($('.unit_price:eq('+i+')').val() && $('.selling_price:eq('+i+')').val() <= 0){
                    if (product_item && product_category != null){
                        $.ajax({
                            method: 'POST',
                            url: '{{ route("get_unit_price") }}',
                            data: {'product_item': product_item, 'product_category': product_category},
                        }).done(function (response) {
                            //console.log(response);
                            if (response.id) {
                                $('.unit_price:eq('+i+')').val(response.unit_price);
                                $('.selling_price:eq('+i+')').val(response.selling_price);
                                calculate();
                            }
                        })
                    }
                }
            });
        }

        function calculate() {
            var total = 0;
            var totalQuantity = 0;
            var allTotal = 0;
            var transport_cost = parseFloat($("#transport_cost").val()||0);
            var paid = parseFloat($('#paid').val()||0);

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;


                $('.total-cost:eq('+i+')').html('Tk ' + (quantity * unit_price).toFixed(2) );
                total += (quantity * unit_price);
                totalQuantity += parseFloat(quantity);
            });

            if ($('#discount_percentage').val() > 0) {
                discount = ($('#discount_percentage').val()*total)/100;
                $('#discount').val(discount);

            }else{
                $('#discount_percentage').val(0);
            }
            var discount = parseFloat($("#discount").val()||0);

            allTotal = (parseFloat(total) + transport_cost) - (discount + paid);
            //due = allTotal - paid;

            $('#due').html('Tk ' + allTotal.toFixed(2));
            $('#total').html('Tk ' + allTotal.toFixed(2));
            $('#total-amount').html('Tk ' + total.toFixed(2));
            $('#total_amount').val(total.toFixed(2));
            $('#total-quantity').html(totalQuantity);
        }

        function initProduct() {
            $('.product').select2();
        }

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });

        $(function () {
            $('body').on('click', '.submission', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Save Purchase Order",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save Purchase Order!'

                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#purchase_order_form').submit();
                    }
                })
            });
        });

    </script>
@endsection
