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
    Stock product
@endsection

@section('content')
    @if(session('error'))
	    <div class="alert alert-danger alert-dismissable">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		  {{session('error')}}
	    </div>
	@endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Stock Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('product_stock.add') }}">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('customer') ? 'has-error' :'' }}">
                                    <label>Customer</label>
                                    <select required class="form-control customer select2" style="width: 100%;" name="customer">
                                        <option value="">Select Customer</option>
                                        @if(old('selected_customer_name'))
                                            <option value="old('customer')" selected>{{ old('selected_customer_name') }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" name="selected_customer_name" class="selected_customer_name">
                                    @error('customer')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" id="add_new_customer_form" style="margin-top: 36px;padding: 2px 10px;font-size: 15px;"><span style="font-weight: bold;">+</span></button>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('warehouse_id') ? 'has-error' :'' }}">
                                    <label>Warehouse</label>

                                    <select class="form-control select2" style="width: 100%;" name="warehouse_id" data-placeholder="Select Warehouse">
                                        {{-- <option value="">Select Warehouse</option> --}}

                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('warehouse')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
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
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group row {{ $errors->has('product_category.'.$loop->index) ? 'has-error' :'' }}">
                                                            <input type="text" class="form-control product_category" name="product_category[]" style="width: 100%;" value="{{ old('product_category.'.$loop->index) }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group row {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                            <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="form-group row {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                            <input type="number" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="form-group row {{ $errors->has('selling_price.'.$loop->index) ? 'has-error' :'' }}">
                                                            <input type="number" class="form-control selling_price" name="selling_price[]" value="{{ old('selling_price.'.$loop->index) }}">
                                                        </div>
                                                    </td>

                                                    <td class="total-cost">Tk 0.00</td>
                                                    <td class="text-center">
                                                        <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @for ($i = 1; $i <=10; $i++)
                                                <tr class="product-item">
                                                    <td>
                                                        <div class="form-group row">
                                                            <input type="text" class="form-control product_item" name="product_item[]" style="width: 100%;">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group row">
                                                            <input type="text" class="form-control product_category" name="product_category[]" style="width: 100%;">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group row">
                                                            <input type="number" step="any" class="form-control quantity" value="6" name="quantity[]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group row">
                                                            <input type="number" class="form-control unit_price" name="unit_price[]" value="0">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group row">
                                                            <input type="number" class="form-control selling_price" name="selling_price[]" value="0">
                                                        </div>
                                                    </td>

                                                    <td class="total-cost">Tk 0.00</td>
                                                    <td class="text-center">
                                                        <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                                    </td>
                                                </tr>
                                        @endfor
                                        @endif
                                        <tfoot>
                                        <tr>
                                            <th>
                                                <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add Product</a>
                                            </th>
                                            <th colspan="" class="text-right">Total Quantity</th>
                                            <th id="total-quantity">0</th>
                                            <th colspan="" class="text-right"></th>
                                            <th colspan="" class="text-right">Total Amount</th>
                                            <th id="total-amount">Tk 0.00</th>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                    </table>
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

    <template id="template-product">
        @php
            $i=1;
        @endphp
        <tr class="product-item" >
            <td>
                <div class="form-group row">
                    <input type="text" class="form-control product_item" name="product_item[]" style="width: 100%;" >
                </div>
            </td>

            <td>
                <div class="form-group row">
                    <input type="text" class="form-control product_category" name="product_category[]" style="width: 100%;" >
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="number" step="any" class="form-control quantity" value="6" name="quantity[]" >
                </div>
            </td>

            <td>
                <div class="form-group row">
                    <input type="number" class="form-control unit_price" name="unit_price[]" value="0" >
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="number" class="form-control selling_price" value="0" name="selling_price[]">
                </div>
            </td>

            <td class="total-cost">Tk 0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
    <!-- Modal for new Customer-->
    <div class="modal fade" id="newCustomerModal" tabindex="-1" aria-labelledby="newCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="min-width: 50% !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="margin-left: 41% !important" id="newCustomerModalLabel">Customer Info</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form style="font-size: 12px;" enctype="multipart/form-data" id="newCustomerForm">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lc_no">Customer Name</label>
                                        <div class="input-group name">
                                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' :'is-valid-border' }}" name="name" value="{{ old('name') }}" placeholder="Name" >
                                        </div>
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="opening_due">Opening Due</label>
                                        <div class="input-group lc_no">
                                            <input type="number" class="form-control {{ $errors->has('opening_due') ? 'is-invalid' :'is-valid-border' }}" name="opening_due" value="0" placeholder="opening_due" >
                                        </div>
                                        @error('opening_due')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Phone</label>
                                        <div class="input-group phone">
                                            <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' :'is-valid-border' }}" name="phone" value="{{ old('phone') }}" placeholder="Phone" >
                                        </div>
                                        @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <div class="input-group address">
                                            <textarea class="form-control is-valid-border" rows="1" name="address"></textarea>
                                        </div>
                                        @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
  <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <!-- bootstrap datepicker -->
  <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
   <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
            initSelect2();

            //Date picker
            $('#date,#next_payment').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            //New Customer Add
            $('#add_new_customer_form').click(function () {
                $('#newCustomerModal').modal('show');
            });
            $('#newCustomerForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData($('#newCustomerForm')[0]);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to add the customer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "Post",
                            url: "{{ route('add_ajax_customer') }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                        }).done(function (response) {
                            if (response.success) {
                                $('#newCustomerModal').modal('hide');
                                Swal.fire(
                                    'Successfully',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    $(".customer").append('<option value="'+response.customer.id+'" selected>'+response.customer.name+'</option>');
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        });

                    }
                });
            });
            //End

            var timer = null;
            $('body').on('keyup','.product_item','.product_category', function(){
                clearTimeout(timer);
                timer = setTimeout(getPrice,7000);
            })

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
                    //delay: 500,
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
                    //delay: 500,
                });

                $('#product-container').append(item);

                if ($('.product-item').length + $('.service-item').length >= 1 ) {
                    $('.btn-remove').show();
                    $('.btn-remove-service').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item').length <= 1 ) {
                    //$('.btn-remove').hide();
                }
            });

            $('#modal-pay-type').change(function () {
                if ($(this).val() == '1' || $(this).val() == '3') {
                    $('#modal-bank-info').hide();
                } else {
                    $('#modal-bank-info').show();
                }
            });

            $('#modal-pay-type').trigger('change');

            var selectedBranch = '{{ old('branch') }}';
            var selectedAccount = '{{ old('account') }}';

            $('#modal-bank').change(function () {
                var bankId = $(this).val();
                $('#modal-branch').html('<option value="">Select Branch</option>');
                $('#modal-account').html('<option value="">Select Account</option>');

                if (bankId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_branch') }}",
                        data: { bankId: bankId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedBranch == item.id)
                                $('#modal-branch').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#modal-branch').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('#modal-branch').trigger('change');
                    });
                }

                $('#modal-branch').trigger('change');
            });

            $('#modal-branch').change(function () {
                var branchId = $(this).val();
                $('#modal-account').html('<option value="">Select Account</option>');

                if (branchId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_bank_account') }}",
                        data: { branchId: branchId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedAccount == item.id)
                                $('#modal-account').append('<option value="'+item.id+'" selected>'+item.account_no+'</option>');
                            else
                                $('#modal-account').append('<option value="'+item.id+'">'+item.account_no+'</option>');
                        });
                    });
                }
            });

            $('#modal-bank').trigger('change');

            $('body').on('keyup', '#discount', function () {
                $('#discount_percentage').val(0);
            });
            // $('body').on('keyup', '.product_item','.product_category', function () {
            //     getPrice();
            // });
            $('body').on('keyup', '.quantity, .unit_price, #transport_cost, #return_amount, #discount_percentage, #vat, #discount, #paid', function () {
                calculate();
            });

            $('body').on('change', '.quantity, .unit_price, #transport_cost, #return_amount, #discount_percentage, #previous_due', function () {
                calculate();
            });

            if ($('.product-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            initProduct();
            calculate();
        });

        function getPrice() {
            $('.product-item').each(function(i, obj) {
                var product_item = $('.product_item:eq('+i+')').val();
                var product_category = $('.product_category:eq('+i+')').val();

                if ($('.unit_price:eq('+i+')').val() && $('.selling_price:eq('+i+')').val() <= 0) {

                    if (product_item && product_category != null) {

                        $.ajax({
                            method: 'POST',
                            url: '{{ route("get_unit_price") }}',
                            data: {'product_item': product_item, 'product_category': product_category},
                        }).done(function (response) {
                            //console.log(response);
                            if (response.id) {
                                $('.unit_price:eq(' + i + ')').val(response.unit_price);
                                $('.selling_price:eq(' + i + ')').val(response.selling_price);
                                calculate();
                            }
                        })
                    }
                }
            });
        }

        function calculate() {
            var productSubTotal = 0;
            var totalQuantity = 0;
            var vat = parseFloat($('#vat').val()||0);
            var discount = parseFloat($('#discount').val()||0);
            var transport_cost = parseFloat($('#transport_cost').val()||0);
            var return_amount = parseFloat($('#return_amount').val()||0);
            var paid = parseFloat($('#paid').val()||0);
            var previous_due = parseFloat($('#previous_due').val()||0);

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();
                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq('+i+')').html('Tk ' + (quantity * unit_price).toFixed(2) );
                productSubTotal += quantity * unit_price;
                totalQuantity += parseFloat(quantity);
            });

            if ($('#discount_percentage').val() > 0) {
                discount = ($('#discount_percentage').val()*productSubTotal)/100;
                $('#discount').val(discount);

            }else{
                $('#discount_percentage').val(0);
            }
            var discount = parseFloat($("#discount").val()||0);
            var productTotalVat = (productSubTotal * vat) / 100;
            $('#product-sub-total').html('Tk ' + productSubTotal.toFixed(2));
            $('#total-amount').html('Tk ' + productSubTotal.toFixed(2));
            $('#vat_total').html('Tk ' + productTotalVat.toFixed(2));

            var total = parseFloat(productSubTotal) + transport_cost + parseFloat(productTotalVat) - parseFloat(discount) - return_amount;

            var due = parseFloat(total) + previous_due - parseFloat(paid);
            $('#total-quantity').html(totalQuantity);
            $('#final-amount').html('Tk ' + total.toFixed(2));
            $('#final_total').html('Tk ' + (total+previous_due).toFixed(2));
            $('#due').html('Tk ' + due.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));

            if (due > 0) {
                $('#tr-next-payment').show();
            } else {
                $('#tr-next-payment').hide();
            }
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
        function initSelect2() {
            $('.select2').select2();
            $('.customer').select2({
                ajax: {
                    url: "{{ route('get_customer_json') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term, // search term
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
            $('.customer').on('select2:select', function (e) {
                let data = e.params.data;
                let index = $(".customer").index(this);
                $('.selected_customer_name').val(data.text);
            });

        }
    </script>
@endsection
