@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .product_suggestion {
            background-color: antiquewhite;
            padding-left: 10px;
            padding-top: 8px;
            padding-bottom: 1px;
        }
    </style>
@endsection

@section('title')
    Sales
@endsection

@section('content')
    <form method="POST" enctype="multipart/form-data" action="{{ route('sale_receipt.edit', ['order'=>$order->id]) }}" id="sale_form">
        @csrf
        <input type="hidden" name="sale_type" value="1">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Order Information</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <input type="hidden" name="invoice_type" value="2">
                        <div class="row">
                            @if (\Illuminate\Support\Facades\Auth::user()->company_branch_id == 0)
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('companyBranch') ? 'has-error' :'' }}">
                                        <label>Branch</label>

                                        <select class="form-control companyBranch select2" style="width: 100%;" name="companyBranch">
                                            <option value="">Select Branch</option>
                                            @foreach($companyBranches as $companyBranch)
                                                <option value="{{ $companyBranch->id }}"  {{ old('companyBranch',$order->company_branch_id) == $companyBranch->id ? 'selected' : '' }}>{{ $companyBranch->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('companyBranch')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('customer') ? 'has-error' :'' }}">
                                    <label>Customer</label>
                                    <select required class="form-control customer select2" style="width: 100%;" name="customer">
                                        <option value="">Select Customer</option>
                                        <option value="{{ $customer->id }}" selected >{{$customer->name??''}}</option>
                                        @if(old('selected_customer_name'))
                                            <option value="{{ old('customer') }}" selected>{{ old('selected_customer_name') }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" name="selected_customer_name" class="selected_customer_name">
                                    @if(auth()->user()->company_branch_id != 0)
                                        <input type="hidden" name="companyBranch" value="{{ auth()->user()->company_branch_id }}" class="companyBranch">
                                    @endif
                                    @error('customer')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" id="add_new_customer_form" style="margin-top: 36px;padding: 2px 10px;font-size: 15px;"><span style="font-weight: bold;">+</span></button>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label> Note </label>

                                    <input class="form-control" type="text" name="note" value="{{ old('note',$order->note) }}">

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date *</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ old('date',date('Y-m-d',strtotime($order->date))) }}" autocomplete="off">
{{--                                        <input hidden type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">--}}
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
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
                        <h3 class="card-title">Product Search</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <input type="search" class="form-control serial_search" id="serial_search" name="serial_search[]" value="" placeholder="Enter product model" autofocus autocomplete="off">
                        </div>
                        <div class="row product_suggestion_container">

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
                    <!-- /.card-header -->

                    <div class="card-body">
{{--                        <div class="form-group">--}}
{{--                            <input type="search" class="form-control serial" id="serial" name="serial[]" value="" placeholder="Enter product model" autofocus autocomplete="off">--}}
{{--                        </div>--}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    {{--                                    <th>Code</th>--}}
                                    <th>Model</th>
                                    <th>Size</th>
                                    <th>Warehouse</th>
                                    <th>Stock</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Cost</th>
                                    <th></th>
                                </tr>
                                </thead>
                                @php
                                    $totalQuantity = 0;
                                @endphp
                                <tbody id="product-container">
                                @if (old('product_serial') != null && sizeof(old('product_serial')) > 0)
                                    @foreach(old('product_serial') as $item)
                                        <tr class="product-item">
                                            {{--                                            <td>--}}
                                            {{--                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">--}}
                                            <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="{{ old('purchase_inventory.'.$loop->index) }}">
                                            <input type="hidden" readonly class="form-control product_serial" name="product_serial[]" value="{{ old('product_serial.'.$loop->index) }}">
                                            {{--                                                </div>--}}
                                            {{--                                            </td>--}}
                                            <td>
                                                <div class="form-group {{ $errors->has('product_item.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control product_item" name="product_item[]" value="{{ old('product_item.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('product_category.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control product_category" name="product_category[]" value="{{ old('product_category.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('warehouse.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="{{ old('warehouse.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('product_stock.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control product_stock" name="product_stock[]" value="{{ old('product_stock.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="total-cost">Tk 0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($order->products as $item)
                                        <tr class="product-item">
                                            <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="{{ $item->purchase_inventory_id }}">
                                            <input type="hidden" readonly class="form-control product_serial" name="product_serial[]"  value="{{ $item->serial }}">
                                            <td>
                                                <div class="form-group {{ $errors->has('product_item.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control product_item" name="product_item[]" value="{{ $item->productItem->name??'' }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" readonly class="form-control product_category" name="product_category[]" value="{{ $item->productCategory->name??'' }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="{{ $item->warehouse->name??'' }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" readonly class="form-control product_stock" name="product_stock[]" value="{{ $item->purchaseInventory->quantity }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" class="form-control quantity" name="quantity[]"  value="{{ $item->quantity }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ $item->unit_price }}">
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
                                    <th></th>
                                    <th></th>
                                    <th class="text-right" colspan="2">Total Quantity</th>
                                    <th id="total-quantity">0</th>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
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
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Type</label>
                                    <select class="form-control select2" id="modal-pay-type" name="payment_type">
                                        <option value="1" {{ old('payment_type') == '1' ? 'selected' : '' }}>Cash</option>
{{--                                        <option value="2" {{ old('payment_type') == '2' ? 'selected' : '' }}>Bank</option>--}}
                                    </select>
                                </div>

                                <div id="modal-bank-info">
                                    <div>
                                        <div class="form-group {{ $errors->has('client_bank_name') ? 'has-error' :'' }}">
                                            <label>Bank Name</label>
                                            <input class="form-control" type="text" name="client_bank_name" placeholder="client_bank_name" value="{{ old('client_bank_name') }}">
                                        </div>
                                        <div class="form-group {{ $errors->has('client_cheque_no') ? 'has-error' :'' }}">
                                            <label>Cheque No.</label>
                                            <input class="form-control" type="text" name="client_cheque_no" placeholder="Enter Client Cheque No." value="{{ old('client_cheque_no') }}">
                                        </div>
                                        {{--                                        <div class="form-group {{ $errors->has('client_amount') ? 'has-error' :'' }}">--}}
                                        {{--                                            <label>Amount</label>--}}
                                        {{--                                            <input class="form-control" type="text" name="client_amount" placeholder="Enter Amount" value="{{ old('client_amount') }}">--}}
                                        {{--                                        </div>--}}
                                        <div class="form-group {{ $errors->has('cheque_date') ? 'has-error' :'' }}">
                                            <label>Cheque Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="cheque_date" name="cheque_date" value="{{ old('cheque_date') }}" autocomplete="off">
                                            </div>
                                            <!-- /.input group -->
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
                                            <div class="form-group {{ $errors->has('previous_due') ? 'has-error' :'' }}">
                                                <input type="text" readonly class="form-control" name="previous_due" id="previous_due" value="{{ $order->paid > $order->invoice_total ? $order->previous_due+($order->paid-$order->invoice_total) : $order->previous_due }}">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="4" class="text-right"> Transport Cost </th>
                                        <td>
                                            <div class="form-group {{ $errors->has('transport_cost') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="transport_cost" id="transport_cost" value="{{ old('transport_cost', $order->transport_cost) }}">
                                            </div>
                                        </td>
                                    </tr>

{{--                                    <tr>--}}
{{--                                        <th colspan="4" class="text-right"> Return Amount </th>--}}
{{--                                        <td>--}}
{{--                                            <div class="form-group {{ $errors->has('return_amount') ? 'has-error' :'' }}">--}}
{{--                                                <input type="text" class="form-control" name="return_amount" id="return_amount" value="{{ old('return_amount', 0) }}">--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
                                    <tr>
                                        <th colspan="4" class="text-right">Discount (Amount) </th>
                                        <td>
                                            <div class="form-group {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="discount" id="discount" value="{{ old('discount', $order->discount) }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Sale Adjustment</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('sale_adjustment') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="sale_adjustment" id="sale_adjustment" value="{{ old('sale_adjustment',$order->sale_adjustment) }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Total Amount</th>
                                        <th id="final_total">Tk 0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Paid</th>
                                        <td>
                                            <div class="form-group {{ $errors->has('paid') ? 'has-error' :'' }}">
                                                <input type="text" class="form-control" name="paid" id="paid" value="{{old('paid',$order->paid) }}">
                                                <input type="hidden" class="form-control pull-right" id="next_payment" name="next_payment" value="" autocomplete="off">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-right">Current Due</th>
                                        <th id="due">Tk 0.00</th>
                                    </tr>
                                    {{--                                    <tr id="tr-next-payment">--}}
                                    {{--                                        <th colspan="4" class="text-right">Next Payment Date</th>--}}
                                    {{--                                        <td>--}}
                                    {{--                                            <div class="form-group {{ $errors->has('next_payment') ? 'has-error' :'' }}">--}}
                                    {{--                                                <div class="input-group date">--}}
                                    {{--                                                    <div class="input-group-addon">--}}
                                    {{--                                                        <i class="fa fa-calendar"></i>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <input type="text" class="form-control pull-right" id="next_payment" name="next_payment" value="{{ old('next_payment') }}" autocomplete="off">--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <!-- /.input group -->--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </td>--}}
                                    {{--                                    </tr>--}}
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <button type="submit" class="btn btn-primary submission ">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <template id="template-product">

        <tr class="product-item">
            {{--            <td>--}}
            {{--                <div class="form-group">--}}
            <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="">
            <input type="hidden" readonly class="form-control product_serial" name="product_serial[]" value="">
            {{--                </div>--}}
            {{--            </td>--}}
            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control product_item" name="product_item[]" value="">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control product_category" name="product_category[]" value="">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control product_stock" name="product_stock[]" value="">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control quantity" name="quantity[]"  value="6">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control unit_price" name="unit_price[]" value="0">
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
                                        <label for="name">Customer Name</label>
                                        <div class="input-group name">
                                            <input type="text" class="form-control new_customer {{ $errors->has('name') ? 'is-invalid' :'is-valid-border' }}" name="name" value="{{ old('name') }}" placeholder="Name" >
                                        </div>
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                @if(auth()->user()->company_branch_id==0)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lc_no">Company Branch *</label>
                                            <div class="input-group name">
                                                <select class="form-control select2 branch" id="branch" name="branch">
                                                    <option>Select Option</option>
                                                    @foreach($companyBranches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @error('branch')
                                            <span class="help-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
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
            $('.select2').select2();
            initSelect2();

            //Date picker
            $('#date, #next_payment, #cheque_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            //New Customer Add
            $('#add_new_customer_form').click(function () {
                $('#newCustomerModal').modal('show');
                let companyBranch = $('.companyBranch').val();
                //console.log($companyBranch);
                $('#branch').select2('val',companyBranch);
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
                                    $(".customer").append('<option value="'+response.customer.id+'" selected>'+response.customer.name+' - '+response.customer.address+' - '+response.customer.mobile_no+'</option>');
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
            //Company Branch
            $('.companyBranch').on('change',function () {
                $('.customer').html('<option value="">Select Customer</option>');
            });
            //Customer
            $('.customer').on('change',function () {
                let customer_id = $(this).val();
                var selectedText = $('.customer option:selected').text();
                if(customer_id==0){
                    let new_customer_name = selectedText.split(' - ')[0];
                    let companyBranch = $('.companyBranch').val();
                    $('#newCustomerModal').modal('show');

                    $('.new_customer').val(new_customer_name);
                    $('#branch').select2('val',companyBranch);
                }
            });

            $('#serial_search').on('input',function () {
                let term = $(this).val();
                $('.product_suggestion_container').html('');
                if (term != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('product_item_suggestions') }}",
                        data: { term: term }
                    }).done(function( response ) {
                        console.log(response);
                        // $('.product_suggestion_container').html('');
                        if (response.success) {
                            $.each(response.productsIds, function (index,row) {
                                let html = '<div class="col-md-4 mb-2 suggestion_item">'+
                                    '<div class="product_suggestion" style="cursor: pointer;" data-serial="'+row.serial+'">'+
                                    '<h6>'+row.product_item.name+" - "+row.product_category.name+" - "+row.warehouse.name+'</h6>'+
                                    '</div>'+
                                    '</div>';

                                $('.product_suggestion_container').append(html);
                            });
                        }
                    });
                }
            });


            {{--$('.customer').on('change',function () {--}}
            {{--    let customer_id = $(this).val();--}}
            {{--    $('#customerPreviousOrder').html('');--}}
            {{--    if (customer_id != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('customer_previous_receipts') }}",--}}
            {{--            data: { customer_id: customer_id }--}}
            {{--        }).done(function( response ) {--}}
            {{--            if (response.success) {--}}
            {{--                console.log(response);--}}
            {{--                $.each(response.order_receipts, function (index,item) {--}}
            {{--                    $('#customerPreviousOrder').append('<tr><td>'+item.order_no+'</td><td>'+item.date+'</td><td><a href="sale-receipt/details/'+item.id+'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a></td></tr>');--}}
            {{--                });--}}
            {{--            }else{--}}
            {{--                $('#customerPreviousOrder').html('');--}}
            {{--            }--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}

            $('#received_by').autocomplete({
                source:function (request, response) {
                    $.getJSON('{{ route("get_received_by_suggestion") }}?term='+request.term, function (data) {
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
                source:function (request, response) {
                    $.getJSON('{{ route("get_serial_suggestion") }}?term='+request.term, function (data) {
                        // console.log(data);
                        var array = $.map(data, function (row) {
                            return {
                                value: row.serial,
                                label: row.product_item.name+" - "+row.product_category.name+" - "+row.warehouse.name
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

            $( "#serial" ).each(function( index ) {
                if ($(this).val() != '') {
                    serials.push($(this).val());
                }
            });

            $( ".product_serial" ).each(function( index ) {
                if ($(this).val() != '') {
                    serials.push($(this).val());
                }
            });

            $('body').on('click', '.btn-remove', function () {
                var serial = $(this).closest('tr').find('.product_serial').val();
                //console.log(serial);
                $(this).closest('.product-item').remove();
                serials.pop($(this).val());

                calculate();

                if ($('.product-item').length + $('.service-item').length <= 1 ) {
                    $('.btn-remove').show();
                    $('.btn-remove-service').hide();
                }

                serials = $.grep(serials, function(value) {
                    return value != serial;
                });

            });

            $('body').on('click', '.product_suggestion', function (e) {
                if (1) {
                    // var serial = $('#serial').val();
                    var serial = $(this).data('serial');
                    $(this).closest('.suggestion_item').fadeOut();
                    $this = $(this);

                    if($.inArray(serial, serials) != -1) {
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
                            text: 'Please enter produce model.',
                        });
                    } else {
                        $.ajax({
                            method: "GET",
                            url: "{{ route('sale_product.details') }}",
                            data: { serial: serial }
                        }).done(function( response ) {
                            //console.log(response);
                            if (response.success) {
                                if ('{{Auth::user()->company_branch_id == 0}}'){
                                    if (response.data.quantity > 6){
                                        var html = '<tr class="product-item"> ' +
                                            ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="'+response.data.id+'"> ' +
                                            '<input type="hidden" readonly class="form-control product_serial" name="product_serial[]" value="'+response.data.serial+'"> <td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="'+response.data.product_item.name+'"> </div></td><td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="'+response.data.product_category.name+'"> </div></td><td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="'+response.data.warehouse.name+'"> </div></td><td> <div class="form-group"> ' +
                                            '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="'+response.data.quantity+'"> </div></td><td> <div class="form-group"> <input type="number" class="form-control quantity" name="quantity[]" max="'+response.data.quantity+'" value="6"> </div></td><td>' +
                                            '<div class="form-group"> <input type="text" class="form-control unit_price" name="unit_price[]" value="'+response.data.selling_price+'"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';
                                        $('#product-container').append(html);
                                    }
                                    else{
                                        var html = '<tr class="product-item"> ' +
                                            ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="'+response.data.id+'"> ' +
                                            '<input type="hidden" readonly class="form-control product_serial" name="product_serial[]" value="'+response.data.serial+'"> <td class="bg-red"> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="'+response.data.product_item.name+'"> </div></td><td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="'+response.data.product_category.name+'"> </div></td><td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="'+response.data.warehouse.name+'"> </div></td><td> <div class="form-group"> ' +
                                            '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="'+response.data.quantity+'"> </div></td><td class="bg-red"> <div class="form-group"> <input type="number" class="form-control quantity" name="quantity[]" max="'+response.data.quantity+'" value="'+response.data.quantity+'"> </div></td><td>' +
                                            '<div class="form-group"> <input type="text" class="form-control unit_price" name="unit_price[]" value="'+response.data.selling_price+'"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';
                                        $('#product-container').append(html);
                                    }
                                }else {
                                    if (response.data.quantity > 6){
                                        var html = '<tr class="product-item"> ' +
                                            ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="'+response.data.id+'"> ' +
                                            '<input type="hidden" readonly class="form-control product_serial" name="product_serial[]" value="'+response.data.serial+'"> <td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="'+response.data.product_item.name+'"> </div></td><td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="'+response.data.product_category.name+'"> </div></td><td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="'+response.data.warehouse.name+'"> </div></td><td> <div class="form-group"> ' +
                                            '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="'+response.data.quantity+'"> </div></td><td> <div class="form-group"> <input type="number" class="form-control quantity" name="quantity[]" max="'+response.data.quantity+'" value="6"> </div></td><td>' +
                                            '<div class="form-group"> <input type="text" class="form-control unit_price" name="unit_price[]" value="'+response.data.selling_price+'"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';
                                        $('#product-container').append(html);
                                    }
                                    else{
                                        var html = '<tr class="product-item"> ' +
                                            ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="'+response.data.id+'"> ' +
                                            '<input type="hidden" readonly class="form-control product_serial" name="product_serial[]" value="'+response.data.serial+'"> <td class="bg-red"> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="'+response.data.product_item.name+'"> </div></td><td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="'+response.data.product_category.name+'"> </div></td><td> ' +
                                            '<div class="form-group"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="'+response.data.warehouse.name+'"> </div></td><td> <div class="form-group"> ' +
                                            '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="'+response.data.quantity+'"> </div></td><td class="bg-red"> <div class="form-group"> <input type="number" class="form-control quantity" name="quantity[]" max="'+response.data.quantity+'" value="'+response.data.quantity+'"> </div></td><td>' +
                                            '<div class="form-group"> <input type="text" class="form-control unit_price" name="unit_price[]" value="'+response.data.selling_price+'"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';
                                        $('#product-container').append(html);
                                    }
                                }
                                // console.log(response.data);
                                serials.push(response.data.serial);
                                //$('.serial').val('');
                                calculate();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
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


            {{--$('body').on('change', '.customer', function (e) {--}}
            {{--    var customer_id = $(this).val();--}}
            {{--    if (customer_id != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('customer_due') }}",--}}
            {{--            data: { customer_id: customer_id }--}}
            {{--        }).done(function( response ) {--}}
            {{--            if (response) {--}}
            {{--                console.log(response);--}}
            {{--                $('#previous_due').val(response);--}}
            {{--                calculate();--}}
            {{--            }--}}
            {{--        });--}}
            {{--    }else{--}}
            {{--        $('#previous_due').val(0);--}}
            {{--        calculate();--}}
            {{--    }--}}
            {{--});--}}
            {{--$('.customer').trigger('change');--}}

            $('body').on('click', '.btn-remove', function () {
                var index = $('.btn-remove').index(this);
                $(this).closest('.product-item').remove();

                $('.available-quantity:eq('+index+')').closest('tr').remove();
                calculate();
            });

            $('body').on('keyup', '.quantity, .unit_price, #transport_cost, #return_amount, #discount_percentage, #vat, #sale_adjustment,#discount, #paid', function () {

                {{--if ('{{Auth::user()->company_branch_id != 0}}'){--}}
                {{--    $('#discount').on('keydown keyup change', function(e){--}}
                {{--        if ($(this).val() >= 500--}}
                {{--            && e.keyCode !== 46 // keycode for delete--}}
                {{--            && e.keyCode !== 8 // keycode for backspace--}}
                {{--        ) {--}}
                {{--            e.preventDefault();--}}
                {{--            $(this).val(500);--}}
                {{--        }--}}
                {{--    });--}}
                {{--}--}}

                calculate();
            });

            $('body').on('change', '.quantity, .unit_price, #transport_cost, #return_amount, #discount_percentage,#sale_adjustment,#previous_due', function () {
                calculate();
            });

            calculate();

            $('#modal-pay-type').change(function () {
                if ($(this).val() == 2) {
                    $('#modal-bank-info').show();
                } else {
                    $('#modal-bank-info').hide();
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
        });

        function calculate() {
            var productSubTotal = 0;
            var totalQuantity = 0;
            var vat = parseFloat($('#vat').val()||0);
            var discount = parseFloat($('#discount').val()||0);
            var sale_adjustment = parseFloat($('#sale_adjustment').val()||0);
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
            $('#vat_total').html('Tk ' + productTotalVat.toFixed(2));

            var total = parseFloat(productSubTotal) + transport_cost + parseFloat(productTotalVat) - parseFloat(discount) - return_amount-parseFloat(sale_adjustment);

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

        $(function () {
            $('body').on('click', '.submission', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Save The Sale Order",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save The Order!'

                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#sale_form').submit();
                    }
                })

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
            $('.customer').on('select2:select', function (e) {
                let data = e.params.data;
                let index = $(".customer").index(this);
                $('.selected_customer_name').val(data.text);
            });

        }

    </script>
@endsection
