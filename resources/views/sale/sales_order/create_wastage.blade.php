@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
          href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <style>
        input.form-control.product_stock {
            width: 75px;
        }

        input.form-control.quantity {
            width: 85px;
        }

        input.form-control.unit_price {
            width: 90px;
        }
    </style>
@endsection

@section('title')
    Wastage Sales
@endsection

@section('content')
    <form method="POST" enctype="multipart/form-data" action="{{ route('sales_wastage.create') }}" id="wastage-sale-form">
        @csrf
        <input type="hidden" name="sale_type" value="2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Order Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group row {{ $errors->has('customer_type') ? 'has-error' :'' }}">
                                    <label>Customer Type </label>
                                    <select class="form-control" id="customer_type" name="customer_type">
                                        <option {{ old('customer_type') == 2 ? 'selected' : '' }} value="2">Old</option>
                                        <option {{ old('customer_type') == 1 ? 'selected' : '' }} value="1">New</option>
                                    </select>
                                    @error('customer_type')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div id="old_customer_area">
                                <div class="col-md-6">
                                    <div class="form-group row {{ $errors->has('customer') ? 'has-error' :'' }}">
                                        <label>Customer</label>

                                        <select class="form-control customer select2" style="width: 100%;"
                                                name="customer">
                                            <option value="">Select Customer</option>

                                            @foreach($customers as $customer)
                                                <option
                                                    value="{{ $customer->id }}" {{ old('customer') == $customer->id ? 'selected' : '' }}>{{ $customer->name.' - '.$customer->address.' - '. $customer->mobile_no}}- {{$customer->branch->name??''}}</option>
                                            @endforeach
                                        </select>

                                        @error('customer')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id="new_customer_area">
                                <div class="col-md-3">
                                    <div class="form-group row {{ $errors->has('customer_name') ? 'has-error' :'' }}">
                                        <label>Customer Name </label>
                                        <input type="text" id="customer_name" name="customer_name"
                                               value="{{ old('customer_name') }}" class="form-control">
                                        @error('customer_name')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row {{ $errors->has('mobile_no') ? 'has-error' :'' }}">
                                        <label>Customer Mobile</label>
                                        <input type="text" id="mobile_no" value="{{ old('mobile_no') }}"
                                               name="mobile_no" class="form-control">
                                        @error('mobile_no')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row {{ $errors->has('address') ? 'has-error' :'' }}">
                                        <label>Customer Address</label>
                                        <input type="text" id="address" value="{{ old('address') }}" name="address"
                                               class="form-control">
                                        @error('address')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date *</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date"
                                               value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}"
                                               autocomplete="off">
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('received_by') ? 'has-error' :'' }}">
                                    <label>Received By</label>

                                    <input class="form-control" type="text" name="received_by"
                                           value="{{ old('received_by') }}">

                                    @error('received_by')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if (\Illuminate\Support\Facades\Auth::user()->company_branch_id == 0)
                                <div class="col-md-4">
                                    <div class="form-group row {{ $errors->has('companyBranch') ? 'has-error' :'' }}">
                                        <label>Branch</label>

                                        <select class="form-control select2" style="width: 100%;" name="companyBranch">
                                            <option value="">Select Branch</option>
                                            @foreach($companyBranches as $companyBranch)
                                                <option
                                                    value="{{ $companyBranch->id }}" {{ old('companyBranch_id') == $companyBranch->id ? 'selected' : '' }}>{{ $companyBranch->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('companyBranch')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label> Note </label>

                                    <input class="form-control" type="text" name="note" value="{{ old('note') }}">

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Products</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="card-body">
                        <div class="form-group row">
                            <input type="search" class="form-control serial" id="serial" name="serial[]" value=""
                                   placeholder="Enter product code" autofocus autocomplete="off">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th> Code</th>
                                    <th> Model</th>
                                    <th> Category</th>
                                    <th> Warehouse</th>
                                    <th>Stock</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Cost</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product_serial') != null && sizeof(old('product_serial')) > 0)
                                    @foreach(old('product_serial') as $item)
                                        <tr class="product-item">

                                            <td>
                                                <div
                                                    class="form-group row {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="hidden" readonly
                                                           class="form-control purchase_inventory"
                                                           name="purchase_inventory[]"
                                                           value="{{ old('purchase_inventory.'.$loop->index) }}">
                                                    <input type="text" readonly class="form-control product_serial"
                                                           name="product_serial[]"
                                                           value="{{ old('product_serial.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group row {{ $errors->has('product_item.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control product_item"
                                                           name="product_item[]"
                                                           value="{{ old('product_item.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group row {{ $errors->has('product_category.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control product_category"
                                                           name="product_category[]"
                                                           value="{{ old('product_category.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group row {{ $errors->has('warehouse.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control warehouse"
                                                           name="warehouse[]"
                                                           value="{{ old('warehouse.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group row {{ $errors->has('product_stock.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" readonly step="any"
                                                           class="form-control product_stock" name="product_stock[]"
                                                           value="{{ old('product_stock.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group row {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control quantity"
                                                           name="quantity[]"
                                                           value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div
                                                    class="form-group row {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control unit_price"
                                                           name="unit_price[]"
                                                           value="{{ old('unit_price.'.$loop->index) }}">
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
                                    <td></td>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right" colspan="2">Total Quantity</th>
                                    <th id="total-quantity">0</th>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- <a role="button" class="btn btn-info btn-sm" id="btn-add-product" style="margin-bottom: 10px">Add Product</a> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Payment</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="bank_append">
                                    <div class="form-group row" >
                                        <label>Payment Type</label>
                                        <select class="form-control select2" id="modal-pay-type" name="payment_type">
                                            <option value="">Select Payment Type</option>
                                            <option value="1" {{ old('payment_type') == '1' ? 'selected' : '' }}>Cash</option>
                                            <option value="2" {{ old('payment_type') == '2' ? 'selected' : '' }}>Bank</option>
                                        </select>
                                    </div>

                                    <div class="modal-bank-info">
                                        <div>
                                            <div
                                                class="form-group row {{ $errors->has('client_bank_name') ? 'has-error' :'' }}">
                                                <label>Bank Name</label>
                                                <input class="form-control" type="text" name="client_bank_name"
                                                       placeholder="client_bank_name">
                                            </div>
                                            <div
                                                class="form-group row {{ $errors->has('client_cheque_no') ? 'has-error' :'' }}">
                                                <label>Cheque No.</label>
                                                <input class="form-control" type="text" name="client_cheque_no"
                                                       placeholder="Enter Client Cheque No.">
                                            </div>
                                            <div
                                                class="form-group row {{ $errors->has('client_amount') ? 'has-error' :'' }}">
                                                <label>Amount</label>
                                                <input class="form-control" type="text" name="client_amount"
                                                       placeholder="Enter Amount">
                                            </div>
                                            <div class="form-group row {{ $errors->has('cheque_date') ? 'has-error' :'' }}">
                                                <label>Cheque Date</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right cheque_date"
                                                           name="cheque_date" value="{{ old('cheque_date') }}"
                                                           autocomplete="off">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="4" class="text-right">Product Sub Total</th>
                                        <th id="product-sub-total">Tk 0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right"> Invoice Total</th>
                                        <th id="final-amount">Tk 0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right"> Previous Due</th>
                                        <td>
                                            <div
                                                class="form-group row {{ $errors->has('previous_due') ? 'has-error' :'' }}">
                                                <input type="text" readonly class="form-control" name="previous_due"
                                                       id="previous_due" value="{{ old('previous_due',0) }}">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="4" class="text-right"> Transport Cost</th>
                                        <td>
                                            <div
                                                class="form-group row {{ $errors->has('transport_cost') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="transport_cost"
                                                       id="transport_cost" value="{{ old('transport_cost', 0) }}">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="4" class="text-right"> Return Amount</th>
                                        <td>
                                            <div
                                                class="form-group row {{ $errors->has('return_amount') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="return_amount"
                                                       id="return_amount" value="{{ old('return_amount', 0) }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right"> Discount (Amount)</th>
                                        <td>
                                            <div class="form-group row {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="discount" id="discount"
                                                       value="{{ old('discount', 0) }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Total Amount</th>
                                        <th id="final_total">Tk 0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Cash Paid</th>
                                        <td>
                                            <div class="form-group row {{ $errors->has('paid') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="paid" id="paid"
                                                       value="{{ empty(old('paid')) ? ($errors->has('paid') ? '' : '0') : old('paid') }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Current Due</th>
                                        <th id="due">Tk 0.00</th>
                                    </tr>
                                    <tr id="tr-next-payment">
                                        <th colspan="4" class="text-right">Next Payment Date</th>
                                        <td>
                                            <div
                                                class="form-group row {{ $errors->has('next_payment') ? 'has-error' :'' }}">
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right" id="next_payment"
                                                           name="next_payment" value="{{ old('next_payment') }}"
                                                           autocomplete="off">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="card-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <button type="submit" class="btn btn-primary pull-right submission">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="template-product">

        <tr class="product-item">
            <td>
                <div class="form-group row">
                    <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]"
                           value="">
                    <input type="text" readonly class="form-control product_serial" name="product_serial[]" value="">
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="text" readonly class="form-control product_item" name="product_item[]" value="">
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="text" readonly class="form-control product_category" name="product_category[]"
                           value="">
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="">
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="text" readonly class="form-control product_stock" name="product_stock[]" value="">
                </div>
            </td>
            <td>
                <div class="form-group row">
                    <input type="text" class="form-control quantity" name="quantity[]" value="6">
                </div>
            </td>

            <td>
                <div class="form-group row">
                    <input type="text" readonly class="form-control unit_price" name="unit_price[]" value="0">
                </div>
            </td>

            <td class="total-cost">Tk 0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>

        <tr>
            <td colspan="7" class="available-quantity" style="font-weight: bold"></td>
        </tr>
    </template>

@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Date picker
            $('#date, #next_payment, .cheque_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('#customer_type').change(function () {
                var customerType = $(this).val();
                if (customerType == '1') {

                    $("#old_customer_area").hide();
                    $("#new_customer_area").show();

                    $('#address').autocomplete({
                        source: function (request, response) {
                            $.getJSON('{{ route("get_customer_address_suggestion") }}?term=' + request.term, function (data) {
                                console.log(data);
                                var array = $.map(data, function (row) {
                                    return {
                                        value: row.address,
                                        label: row.address,
                                    }
                                });
                                response($.ui.autocomplete.filter(array, request.term));
                            })
                        },
                        minLength: 2,
                        delay: 500,
                    });

                    $('#mobile_no').autocomplete({
                        source: function (request, response) {
                            $.getJSON('{{ route("get_customer_mobile_no_suggestion") }}?term=' + request.term, function (data) {
                                console.log(data);
                                var array = $.map(data, function (row) {
                                    return {
                                        value: row.mobile_no,
                                        label: row.mobile_no,
                                    }
                                });
                                response($.ui.autocomplete.filter(array, request.term));
                            })
                        },
                        minLength: 2,
                        delay: 500,
                    });

                    $('#customer_name').autocomplete({
                        source: function (request, response) {
                            $.getJSON('{{ route("get_customer_name_suggestion") }}?term=' + request.term, function (data) {
                                console.log(data);
                                var array = $.map(data, function (row) {
                                    return {
                                        value: row.name,
                                        label: row.name,
                                    }
                                });
                                response($.ui.autocomplete.filter(array, request.term));
                            })
                        },
                        minLength: 2,
                        delay: 500,
                    });

                } else {
                    $("#new_customer_area").hide();
                    $("#old_customer_area").show();
                }

            });

            $('#customer_type').trigger("change");

            $('#received_by').autocomplete({
                source: function (request, response) {
                    $.getJSON('{{ route("get_received_by_suggestion") }}?term=' + request.term, function (data) {
                        console.log(data);
                        var array = $.map(data, function (row) {
                            return {
                                value: row.received_by,
                                label: row.received_by,
                            }
                        });
                        response($.ui.autocomplete.filter(array, request.term));
                    })
                },
                minLength: 2,
                delay: 500,
            });

            $('.serial').autocomplete({
                source: function (request, response) {
                    $.getJSON('{{ route("get_serial_suggestion") }}?term=' + request.term, function (data) {
                        // console.log(data);
                        var array = $.map(data, function (row) {
                            return {
                                value: row.serial,
                                label: row.serial + " - " + row.product_item.name + " - " + row.product_category.name + " - " + row.warehouse.name
                            }
                        });
                        response($.ui.autocomplete.filter(array, request.term));
                    })
                },
                minLength: 2,
                delay: 500,
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

            var serials = [];

            $("#serial").each(function (index) {
                if ($(this).val() != '') {
                    serials.push($(this).val());
                }
            });

            $('body').on('click', '.btn-remove', function () {
                var serial = $(this).closest('tr').find('.serial').val();
                $(this).closest('.product-item').remove();
                serials.pop($(this).val());
                calculate();

                if ($('.product-item').length + $('.service-item').length <= 1) {
                    $('.btn-remove').show();
                    $('.btn-remove-service').hide();
                }

                serials = $.grep(serials, function (value) {
                    return value != serial;
                });

            });

            $('body').on('keypress', '.serial', function (e) {
                if (e.keyCode == 13) {
                    var serial = $(this).val();
                    $this = $(this);


                    if ($.inArray(serial, serials) != -1) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Already exist in list.',
                        });

                        return false;
                    }

                    if (serial == '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please enter produce code.',
                        });
                    } else {
                        $.ajax({
                            method: "GET",
                            url: "{{ route('sale_product.details') }}",
                            data: {serial: serial}
                        }).done(function (response) {
                            //console.log(response);
                            if (response.success) {
                                if ('{{Auth::user()->company_branch_id == 0}}') {
                                    if (response.data.quantity >= 6) {
                                        var html = '<tr class="product-item"> <td> <div class="form-group row">' +
                                            ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="' + response.data.id + '"> ' +
                                            '<input type="text" readonly class="form-control product_serial" name="product_serial[]" value="' + response.data.serial + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="' + response.data.product_item.name + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="' + response.data.product_category.name + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="' + response.data.warehouse.name + '"> </div></td><td> <div class="form-group row"> ' +
                                            '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="' + response.data.quantity + '"> </div></td><td> <div class="form-group row"> <input type="number" class="form-control quantity" name="quantity[]" max="' + response.data.quantity + '" value="6"> </div></td><td>' +
                                            '<div class="form-group row"> <input type="text" class="form-control unit_price" name="unit_price[]" value="' + response.data.selling_price + '"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';
                                        $('#product-container').append(html);
                                    } else {
                                        var html = '<tr class="product-item"> <td> <div class="form-group row">' +
                                            ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="' + response.data.id + '"> ' +
                                            '<input type="text" readonly class="form-control product_serial" name="product_serial[]" value="' + response.data.serial + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="' + response.data.product_item.name + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="' + response.data.product_category.name + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="' + response.data.warehouse.name + '"> </div></td><td> <div class="form-group row"> ' +
                                            '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="' + response.data.quantity + '"> </div></td><td> <div class="form-group row"> <input type="number" class="form-control quantity" name="quantity[]" max="' + response.data.quantity + '" value="' + response.data.quantity + '"> </div></td><td>' +
                                            '<div class="form-group row"> <input type="text" class="form-control unit_price" name="unit_price[]" value="' + response.data.selling_price + '"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';
                                        $('#product-container').append(html);
                                    }
                                } else {
                                    if (response.data.quantity >= 6) {
                                        var html = '<tr class="product-item"> <td> <div class="form-group row">' +
                                            ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="' + response.data.id + '"> ' +
                                            '<input type="text" readonly class="form-control product_serial" name="product_serial[]" value="' + response.data.serial + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="' + response.data.product_item.name + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="' + response.data.product_category.name + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="' + response.data.warehouse.name + '"> </div></td><td> <div class="form-group row"> ' +
                                            '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="' + response.data.quantity + '"> </div></td><td> <div class="form-group row"> <input type="number" class="form-control quantity" name="quantity[]" max="' + response.data.quantity + '" value="6"> </div></td><td>' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control unit_price" name="unit_price[]" value="' + response.data.selling_price + '"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';
                                        $('#product-container').append(html);
                                    } else {
                                        var html = '<tr class="product-item"> <td> <div class="form-group row">' +
                                            ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="' + response.data.id + '"> ' +
                                            '<input type="text" readonly class="form-control product_serial" name="product_serial[]" value="' + response.data.serial + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="' + response.data.product_item.name + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="' + response.data.product_category.name + '"> </div></td><td> ' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="' + response.data.warehouse.name + '"> </div></td><td> <div class="form-group row"> ' +
                                            '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="' + response.data.quantity + '"> </div></td><td> <div class="form-group row"> <input type="number" class="form-control quantity" name="quantity[]" max="' + response.data.quantity + '" value="' + response.data.quantity + '"> </div></td><td>' +
                                            '<div class="form-group row"> <input type="text" readonly class="form-control unit_price" name="unit_price[]" value="' + response.data.selling_price + '"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';
                                        $('#product-container').append(html);
                                    }
                                }
                                // console.log(response.data);
                                serials.push(response.data.serial);
                                $('.serial').val('');
                                calculate();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'This product is not available',
                                });
                                calculate();
                            }
                        });
                    }
                    return false; // prevent the button click from happening
                }
            });

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);
                $('#product-container').append(item);
            });


            $('body').on('change', '.customer', function (e) {
                var customer_id = $(this).val();
                if (customer_id != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('customer_due') }}",
                        data: {customer_id: customer_id}
                    }).done(function (response) {
                        if (response) {
                            console.log(response);
                            $('#previous_due').val(response);
                            calculate();
                        }
                    });
                } else {
                    $('#previous_due').val(0);
                    calculate();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                var index = $('.btn-remove').index(this);
                $(this).closest('.product-item').remove();

                $('.available-quantity:eq(' + index + ')').closest('tr').remove();
                calculate();
            });

            $('body').on('keyup', '.quantity, .unit_price, #transport_cost, #return_amount, #discount_percentage, #vat, #discount, #paid', function () {
                calculate();
            });

            $('body').on('change', '.quantity, .unit_price, #transport_cost, #return_amount, #discount_percentage, #previous_due', function () {
                calculate();
            });

            calculate();

            $('#modal-pay-type').change(function () {
                // alert($(this).val());
                if ($(this).val() == 2) {
                    $('.modal-bank-info').show();
                } else {
                    $('.modal-bank-info').hide();
                }
            });

            $('#modal-pay-type').trigger('change');

            $('#modal-pay-type').trigger('change');


            // $('#add_bank_btn').click(function () {
            //     var html = $('.modal-bank-info').html();
            //     var item = $(html);
            //     $('#bank_append').append(item);
            //     $('.cheque_date').datepicker({
            //         autoclose: true,
            //         format: 'yyyy-mm-dd'
            //     });
            //
            //     // alert('sdfd');
            // });

            // if($('.modal-bank-info').length==0){
            //     alert('sdsdsd')
            //     $('.remove_bank_btn').show();
            // }else {
            //     $('.remove_bank_btn').show();
            // }

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
                        data: {bankId: bankId}
                    }).done(function (response) {
                        $.each(response, function (index, item) {
                            if (selectedBranch == item.id)
                                $('#modal-branch').append('<option value="' + item.id + '" selected>' + item.name + '</option>');
                            else
                                $('#modal-branch').append('<option value="' + item.id + '">' + item.name + '</option>');
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
                        data: {branchId: branchId}
                    }).done(function (response) {
                        $.each(response, function (index, item) {
                            if (selectedAccount == item.id)
                                $('#modal-account').append('<option value="' + item.id + '" selected>' + item.account_no + '</option>');
                            else
                                $('#modal-account').append('<option value="' + item.id + '">' + item.account_no + '</option>');
                        });
                    });
                }
            });

            $('#modal-bank').trigger('change');
        });

        function calculate() {
            var productSubTotal = 0;
            var totalQuantity = 0;
            var vat = parseFloat($('#vat').val() || 0);
            var discount = parseFloat($('#discount').val() || 0);
            var transport_cost = parseFloat($('#transport_cost').val() || 0);
            var return_amount = parseFloat($('#return_amount').val() || 0);
            var paid = parseFloat($('#paid').val() || 0);
            var previous_due = parseFloat($('#previous_due').val() || 0);

            $('.product-item').each(function (i, obj) {
                var quantity = $('.quantity:eq(' + i + ')').val();
                var unit_price = $('.unit_price:eq(' + i + ')').val();
                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq(' + i + ')').html('Tk ' + (quantity * unit_price).toFixed(2));
                productSubTotal += quantity * unit_price;
                totalQuantity += parseFloat(quantity);
            });

            if ($('#discount_percentage').val() > 0) {
                discount = ($('#discount_percentage').val() * productSubTotal) / 100;
                $('#discount').val(discount);

            } else {
                $('#discount_percentage').val(0);
            }
            var discount = parseFloat($("#discount").val() || 0);
            var productTotalVat = (productSubTotal * vat) / 100;
            $('#product-sub-total').html('Tk ' + productSubTotal.toFixed(2));
            $('#vat_total').html('Tk ' + productTotalVat.toFixed(2));

            var total = parseFloat(productSubTotal) + transport_cost + parseFloat(productTotalVat) - parseFloat(discount) - return_amount;

            var due = parseFloat(total) + previous_due - parseFloat(paid);
            $('#total-quantity').html(totalQuantity);
            $('#final-amount').html('Tk ' + total.toFixed(2));
            $('#final_total').html('Tk ' + (total + previous_due).toFixed(2));
            $('#due').html('Tk ' + due.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));

            if (due > 0) {
                $('#tr-next-payment').show();
            } else {
                $('#tr-next-payment').hide();
            }
        }

        $(function () {
            $('body').on('click', '.submission', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Save Wastage Sale",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save Wastage Sale!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#wastage-sale-form').submit();
                    }
                })

            });
        });
    </script>
@endsection
