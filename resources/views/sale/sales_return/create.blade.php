@extends('layouts.app')
@section('title','Sale Return')
@section('style')
    <style>
        .btn-info {
            color: #fff;
            background-color: #e34f0d;
            border-color: #e34f0d;
            card-shadow: none;
        }
        .btn-info:hover {
            color: #fff;
            background-color: #e34f0d;
            border-color: #e34f0d;
        }
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            white-space: nowrap;
        }
        input.form-control.quantity{
            width: 100px;
        }
        input.form-control.unit_price{
            width: 100px;
        }
        input.form-control.available_quantity{
            width: 120px;
        }
        input.form-control.unit{
            width: 60px;
        }
        .input-group-addon i{
            padding-top:10px;
            padding-right: 10px;
            border: 1px solid #cecccc;
            padding-bottom: 10px;
            padding-left: 10px;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            text-align: center;
        }
        .table-bordered>tfoot>tr>td {
            white-space: nowrap;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('sales_return.add') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="row pb-3">
                            @if(auth()->user()->company_branch_id == 0)
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('companyBranch') ? 'has-error' :'' }}">
                                    <label>Branch</label>
                                    <select class="form-control select2 companyBranch" style="width: 100%;" id="companyBranch" name="companyBranch" data-placeholder="Select company Branch">
                                        <option value="">Select Branch</option>
                                        @foreach($companyBranches as $companyBranch)
                                            <option value="{{ $companyBranch->id }}"  {{ old('companyBranch') == $companyBranch->id ? 'selected' : '' }}>{{ $companyBranch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('companyBranch')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('sale_order') ? 'has-error' :'' }}">
                                    <label>Return Order</label>
                                    <select required class="form-control sale_order select2" style="width: 100%;" name="sale_order">
                                        <option value="">Select Return Order</option>
                                        @if(old('selected_return_order'))
                                            <option value="{{ old('sale_order') }}" selected>{{ old('selected_return_order') }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" name="selected_return_order" class="selected_return_order">
                                    @if(auth()->user()->company_branch_id != 0)
                                        <input type="hidden" name="companyBranch" value="{{auth()->user()->company_branch_id}}" class="companyBranch">
                                    @endif
                                    @error('sale_order')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right date-picker" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('d-m-Y')) : old('date') }}" autocomplete="off">
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
                                <tr style="border: 2.3px solid black !important">
                                    <th style="white-space: nowrap" >Product</th>
                                    <th style="white-space: nowrap" >Size</th>
                                    <th style="white-space: nowrap" >Buying Price</th>
                                    <th style="white-space: nowrap" >Available to Return</th>
                                    <th style="white-space: nowrap" >Return Quantity</th>
                                    <th style="white-space: nowrap" >Total Cost</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->


                    <div class="card-footer" >
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
            initSelect2();

            var productSubTotal = 0;

            $('.companyBranch').on('change',function () {
                $('.sale_order').html('<option value="">Select Return Order</option>');
                $('#product-container').empty();
            });


            $('body').on('change', '.sale_order', function () {
                var saleOrderId = $(this).val();

                if (saleOrderId !== '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_sale_return_details') }}",
                        data: { saleOrderId: saleOrderId }
                    }).done(function (response) {
                        console.log(response); // Log the response to the console

                        var products = response.products;
                        var productContainer = $('#product-container');

                        // Clear existing products
                        productContainer.empty();

                        // Add new products
                        $.each(products, function (index, item) {
                            var newRow = '' +
                                '<tr class="product-item">' +
                                '<td>' +
                                '<div class="form-group">' +
                                '<input type="text" step="any" id="product" class="form-control product" name="product[]" value="' + item.model.name + '" readonly>' +
                                '<input type="hidden" step="any" class="form-control inventory_id" name="inventory_id[]" value="' + item.purchase_inventory_id + '" readonly>' +
                                '</div>' +
                                '</td>' +
                                '<td>' +
                                '<div class="form-group">' +
                                '<input type="text" step="any" class="form-control size" name="size[]" value="' + item.size.name + '" readonly>' +
                                '</div>' +
                                '</td>' +
                                '<td>' +
                                '<div class="form-group">' +
                                '<input type="text" step="any" class="form-control unit_price" name="unit_price[]" value="' + item.unit_price + '" readonly>' +
                                '</div>' +
                                '</td>' +
                                '<td>' +
                                '<div class="form-group">' +
                                '<input type="text" step="any" class="form-control available_quantity" name="available_quantity[]" value="' + item.quantity+ '" readonly>' +
                                '</div>' +
                                '</td>' +
                                '<td>' +
                                '<div class="form-group">' +
                                '<input type="text" step="any" class="form-control quantity" name="quantity[]" value="" >' +
                                '</div>' +
                                '</td>' +
                                '<td class="total-cost">' +
                                '0.00' +
                                '</td>' +
                                '<td class="text-center">' +
                                '<a role="button" class="btn btn-danger btn-sm btn-remove">X</a>' +
                                '</td>' +
                                '</tr>';

                            productContainer.append(newRow);
                        });
                        var anotherRow = '' +
                            '<tr class="product-item">' +
                            '<th colspan="5" class="text-right">' +
                            'Total Amount' +
                            '</th>' +
                            '<th id="total-amount">' +
                            '0.00' +
                            '</th>' +
                            '</th>' +
                            '<th>' +

                            '</th>' +
                            '</tr>';

                        productContainer.append(anotherRow);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX request failed: " + textStatus, errorThrown);
                    });
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('keyup', '.quantity', function () {
                // Get the index of the current quantity input
                var index = $(this).closest('.product-item').index();
                calculate(index);
                updateTotalAmount();
            });

            $('body').on('change', '.quantity', function () {
                // Get the index of the current quantity input
                var index = $(this).closest('.product-item').index();
                calculate(index);
                updateTotalAmount();
            });

            function calculate(index) {
                var $quantityInput = $('.quantity:eq(' + index + ')');
                var $total_cost = $('.total-cost:eq(' + index + ')');
                var quantity = parseFloat($quantityInput.val()) || 0;
                var available_quantity = $('.available_quantity:eq(' + index + ')').val();
                var unit_price = $('.unit_price:eq(' + index + ')').val();

                if (quantity > available_quantity) {
                    alert('Quantity cannot exceed the available quantity.');
                    $quantityInput.val('');
                    $total_cost.val('');

                }

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq(' + index + ')').html('Tk ' + (quantity * unit_price).toFixed(2));
                productSubTotal += quantity * unit_price;
                updateTotalAmount();
            }
            function updateTotalAmount() {
                var totalAmount = 0;

                $('.total-cost').each(function () {
                    totalAmount += parseFloat($(this).text().replace('Tk ', '')) || 0;
                });

                $('#total-amount').html('Tk ' + totalAmount.toFixed(2));
            }

        })


        function initSelect2() {
            $('.select2').select2();
            $('.sale_order').select2({
                ajax: {
                    url: "{{ route('get_sales_order_json') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term, // search term
                            branch: $('.companyBranch').val()
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            $('.sale_order').on('select2:select', function (e) {
                let data = e.params.data;
                let index = $(".sale_order").index(this);
                $('.selected_return_order').val(data.text);
            });

        }

    </script>
@endsection
