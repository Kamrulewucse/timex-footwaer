@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        input.form-control.product_stock {
            width: 115px;
        }
    </style>
@endsection

@section('title')
    Sales Order edit
@endsection

@section('content')
    <form method="POST" enctype="multipart/form-data" action="{{ route('sale_receipt_warehouse_pending.edit', ['order'=>$order->id]) }}" id="sales_order_form">
        @csrf
        <input type="hidden" name="sale_type" value="{{ $order->sale_type }}">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Order Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('customer') ? 'has-error' :'' }}" id="form-group-customer">
                                    <label>Customer *</label>
                                    <select class="form-control select2 customer" style="width: 100%;" id="customer" name="customer" required>
                                        <option value="">Select Customer </option>
                                        @foreach (App\Model\Customer::where('status',1)->with('branch')->get() as $customer)
                                            <option value="{{ $customer->id }}" @if (old('customer', $order->customer_id) == $customer->id) selected @endif>{{ $customer->name.' - '.$customer->address.' - '.$customer->mobile_no }} - {{$customer->branch->name??''}}</option>
                                        @endforeach
                                    </select>
                                    @error('customer')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date *</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ old('date', date('Y-m-d', strtotime($order->date))) }}" autocomplete="off">
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

                                    <input class="form-control" type="text" name="received_by" value="{{ old('received_by', $order->received_by) }}">

                                    @error('received_by')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if($order->invoice_type == 1)
                                <div class="col-md-4">
                                    <div class="form-group row {{ $errors->has('invoice_type') ? 'has-error' :'' }}">
                                        <label>Invoice Type *</label>
                                        <select class="form-control" id="invoice_type" name="invoice_type">
                                            {{--                                    <option value="">Invoice Type</option>--}}
                                            <option {{ old('invoice_type') == 2 ? 'selected' : '' }} value="2">Warehouse Approved</option>
                                        </select>
                                        @error('invoice_type')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group row {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label> Note </label>

                                    <input class="form-control" type="text" name="note" value="{{ old('note', $order->note) }}">

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if (\Illuminate\Support\Facades\Auth::user()->company_branch_id == 0)
                                <div class="col-md-4">
                                    <div class="form-group row {{ $errors->has('') ? 'has-error' :'' }}" id="form-group-company">
                                        <label>Company *</label>
                                        <select class="form-control select2 company" style="width: 100%;" id="company" name="company">
                                            <option value="">Select Company </option>
                                            @foreach (App\Model\CompanyBranch::where('status',1)->get() as $company)
                                                <option value="{{ $company->id }}" @if (old('company', $order->company_branch_id) == $company->id) selected @endif>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company')
                                        <span class="help-block" style="color: red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
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
                            <input type="search" class="form-control serial" id="serial" name="serial[]" value="" placeholder="Enter product code" autofocus autocomplete="off">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th> Code </th>
                                    <th> Model </th>
                                    <th> Category </th>
                                    <th> Warehouse </th>
                                    <th width="60">Stock</th>
                                    <th width="80">Quantity</th>
                                    <th width="80">Unit Price</th>
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
                                            <td class="invoice_color_serial bg-color-{{ old('product_serial.'.$loop->index) }}">
                                                <div class="form-group row {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="{{ old('purchase_inventory.'.$loop->index) }}">
                                                    <input type="text" readonly class="form-control product_serial" name="product_serial[]" value="{{ old('product_serial.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row {{ $errors->has('product_item.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control product_item" name="product_item[]" value="{{ old('product_item.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row {{ $errors->has('product_category.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control product_category" name="product_category[]" value="{{ old('product_category.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row {{ $errors->has('warehouse.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="{{ old('warehouse.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row {{ $errors->has('product_stock.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control product_stock" name="product_stock[]" value="{{ old('product_stock.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group row {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group row {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="total-cost">Tk 0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>

                                        {{-- <tr>
                                            <td colspan="7" class="available-quantity" style="font-weight: bold"></td>
                                        </tr> --}}
                                    @endforeach

                                @else
                                    @if ($order->invoice_type == 1)
                                        @foreach ($order->notApproveProducts as $item)
                                            @php
                                                $totalQuantity += $item->quantity;
                                            @endphp
                                            <tr class="product-item">
                                                <td class="invoice_color_serial bg-color-{{ $item->serial }}">
                                                    <div class="form-group row">
                                                        <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="{{ $item->purchase_inventory_id }}">
                                                        <input type="text" readonly class="form-control product_serial" name="product_serial[]" value="{{ $item->serial }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group row">
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
                                                        <input type="text" value="0" class="form-control quantity_assign">
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
                                    @else
                                        @foreach ($order->products as $item)
                                            <tr class="product-item">
                                                <td class="color_serial">
                                                    <div class="form-group row">
                                                        <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="{{ $item->purchase_inventory_id }}">
                                                        <input type="text" readonly class="form-control product_serial" name="product_serial[]" value="{{ $item->serial }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group row">
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
                                                        <input type="text" value="0" class="form-control quantity_assign" name="quantity_assign[]">
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
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right" colspan="2">Total Quantity</th>
                                    <th id="total-quantity">{{$totalQuantity}}</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right" colspan="2">Approved Quantity</th>
                                    <th id="approved_total">0</th>
                                    <td></td>
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
                        {{-- <h3 class="card-title">Payment</h3> --}}
                    </div>
                    <!-- /.box-header -->
                    @if ($order->invoice_type != 1)
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label>Payment Type</label>
                                        <select class="form-control select2" id="modal-pay-type" name="payment_type">
                                            {{--                                    <option value="1" {{ old('payment_type') == '1' ? 'selected' : '' }}>Cash</option>--}}
                                            <option value="">Select Payment Type</option>
                                            @if ($order->client_amount > 0)
                                                <option value="2" {{'selected'}}>Bank</option>
                                            @else
                                                <option value="2" {{ old('payment_type') == '2' ? 'selected' : '' }}>Bank</option>
                                            @endif
                                            {{--                                    <option value="3" {{ old('payment_type') == '3' ? 'selected' : '' }}>Mobile Banking</option>--}}
                                        </select>
                                    </div>

                                    <div id="modal-bank-info">
                                        <div>
                                            <div class="form-group row {{ $errors->has('client_bank_name') ? 'has-error' :'' }}">
                                                <label>Client Bank Name</label>
                                                <input class="form-control" type="text" name="client_bank_name" placeholder="client_bank_name" value="{{ old('client_bank_name',$order->client_bank_name??'') }}">
                                            </div>
                                            <div class="form-group row {{ $errors->has('client_cheque_no') ? 'has-error' :'' }}">
                                                <label>Client Cheque No.</label>
                                                <input class="form-control" type="text" name="client_cheque_no" placeholder="Enter Client Cheque No." value="{{ old('client_cheque_no',$order->client_cheque_no??'') }}">
                                            </div>
                                            <div class="form-group row {{ $errors->has('client_amount') ? 'has-error' :'' }}">
                                                <label>Amount</label>
                                                <input class="form-control" type="text" name="client_amount" placeholder="Enter Amount" value="{{ old('client_amount',$order->client_amount??'') }}">
                                            </div>

                                            <div class="form-group row {{ $errors->has('cheque_date') ? 'has-error' :'' }}">
                                                <label>Cheque Date</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right" id="cheque_date" name="cheque_date" value="{{ old('cheque_date',$order->cheque_date??'') }}" autocomplete="off">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th colspan="4" class="text-right">Product Sub Total</th>
                                            <th id="product-sub-total">Tk 0.00</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right"> Discount (Amount) </th>
                                            <td>
                                                <div class="form-group row {{ $errors->has('discount') ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control" name="discount" id="discount" value="{{ old('discount', $order->discount) }}">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right"> Transport Cost </th>
                                            <td>
                                                <div class="form-group row {{ $errors->has('transport_cost') ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control" name="transport_cost" id="transport_cost" value="{{ old('transport_cost', $order->transport_cost) }}">
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th colspan="4" class="text-right"> Invoice Total</th>
                                            <th id="final-amount">Tk 0.00</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right"> Previuos Due</th>
                                            <td>
                                                <div class="form-group row {{ $errors->has('previous_due') ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control" name="previous_due" id="previous_due" value="{{ old('previous_due',0) }}">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right"> Return Amount </th>
                                            <td>
                                                <div class="form-group row {{ $errors->has('return_amount') ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control" name="return_amount" id="return_amount" value="{{ old('return_amount', $order->return_amount) }}">
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
                                                    <input type="text" class="form-control" name="paid" id="paid" value="{{ old('paid', $order->paid) }}">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right">Due</th>
                                            <th id="due">Tk 0.00</th>
                                        </tr>
                                        <tr id="tr-next-payment">
                                            <th colspan="4" class="text-right">Next Payment Date</th>
                                            <td>
                                                <div class="form-group row {{ $errors->has('next_payment') ? 'has-error' :'' }}">
                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="next_payment" name="next_payment" value="{{ old('next_payment', $order->next_payment?date('Y-m-d', strtotime($order->next_payment)):'') }}" autocomplete="off">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                @endif
                <!-- /.box-body -->

                    <div class="card-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <button type="submit" class="btn btn-primary submission">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group row">
                    <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="">
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
                    <input type="text" readonly class="form-control product_category" name="product_category[]" value="">
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
                    <input type="text" class="form-control quantity" name="quantity[]"  value="6">
                </div>
            </td>

            <td>
                <div class="form-group row">
                    <input type="text" class="form-control unit_price" name="unit_price[]" value="0">
                </div>
            </td>

            <td class="total-cost">Tk 0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>

        <tr>
            <td colspan="5" class="available-quantity" style="font-weight: bold"></td>
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
            $('#date, #next_payment,#cheque_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('#modal-pay-type').change(function () {
                if ($(this).val() == 2 || '{{$order->client_amount > 0}}') {
                    $('#modal-bank-info').show();
                } else {
                    $('#modal-bank-info').hide();
                }
            });
            $('#modal-pay-type').trigger('change');

            $('.serial').autocomplete({
                source:function (request, response) {
                    $.getJSON('{{ route("get_serial_suggestion") }}?term='+request.term, function (data) {
                        // console.log(data);
                        var array = $.map(data, function (row) {
                            return {
                                value: row.serial,
                                label: row.serial+" - "+row.product_item.name+" - "+row.product_category.name+" - "+row.warehouse.name
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
            var codes = '{{ $order->products->pluck("serial") }}';
            var codes = codes.replace(/&quot;/g,'"');
            // console.log(codes);
            $.each(JSON.parse(codes), function( index, item ) {
                serials.push(item);
            });

            $( "#serial" ).each(function( index ) {
                if ($(this).val() != '') {
                    serials.push($(this).val());
                }
            });

            $('body').on('click', '.btn-remove', function () {
                var serial = $(this).closest('tr').find('.product_serial').val();
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item') ) {
                    $('.btn-remove').show();
                }

                serials = $.grep(serials, function(value) {
                    return value != serial;
                });

            });
            $('.invoice_color_serial').css({
                'background-color': 'red',
            });

            $('body').on('keypress', '.serial', function (e) {
                if (e.keyCode == 13) {
                    var serial = $(this).val();
                    var selectItem =  $(".bg-color-"+serial);

                    var quantity = selectItem.closest('tr').find('.quantity').val();
                    var quantityAssign = selectItem.closest('tr').find('.quantity_assign').val();

                    var invoiceType = '{{ $order->invoice_type }}';
                    if(invoiceType == 1){
                        if ($.inArray(serial, serials) == -1){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Please enter Right code.',
                            });
                            $('.serial').val('');
                            return false;
                        }

                        if($.inArray(serial, serials) != -1) {
                            if(quantity == parseFloat(quantityAssign) ){
                                Swal.fire(
                                    'error!',
                                    'Warehouse matched already !',
                                    'error',
                                );
                            }
                            else if (quantity < 6){
                                var checkAssignQty = selectItem.closest('tr').find('.quantity_assign').val(parseFloat(quantityAssign)+parseFloat(quantity));
                                var assignNewQty = selectItem.closest('tr').find('.quantity_assign').val();
                            }else if (quantity == 6){
                                var checkAssignQty = selectItem.closest('tr').find('.quantity_assign').val(parseFloat(quantityAssign)+6);
                                var assignNewQty = selectItem.closest('tr').find('.quantity_assign').val();
                            }
                            else if (quantity > 6  && (parseFloat(quantityAssign)!=0) && (!(parseFloat(quantityAssign) % 6)) ){
                                var sub = (parseFloat(quantity) - parseFloat(quantityAssign));
                                if(sub < 6 ){
                                    var checkAssignQty = selectItem.closest('tr').find('.quantity_assign').val(parseFloat(quantityAssign)+parseFloat(sub));
                                    var assignNewQty = selectItem.closest('tr').find('.quantity_assign').val();
                                }else{
                                    var checkAssignQty = selectItem.closest('tr').find('.quantity_assign').val(parseFloat(quantityAssign)+6);
                                    var assignNewQty = selectItem.closest('tr').find('.quantity_assign').val();
                                }

                            } else{
                                var checkAssignQty = selectItem.closest('tr').find('.quantity_assign').val(parseFloat(quantityAssign)+6);
                                var assignNewQty = selectItem.closest('tr').find('.quantity_assign').val();
                            }
                            calculate();

                            if (assignNewQty == quantity){
                                $('.serial').val('');
                                $(".bg-color-"+serial).css('backgroundColor','green');
                            }else {
                                $('.serial').val('');
                                return false;
                            }
                            Swal.fire(
                                'Success!',
                                'Warehouse matched exist in list.',
                                'success',
                            );
                            $('.serial').val('');
                            return false;

                        }
                    }else{
                        if($.inArray(serial, serials) != -1) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Already exist in list.',
                            });
                            return false;
                        }
                    }

                    {{--if (serial == '') {--}}
                    {{--    Swal.fire({--}}
                    {{--        icon: 'error',--}}
                    {{--        title: 'Oops...',--}}
                    {{--        text: 'Please enter produce code.',--}}
                    {{--    });--}}
                    {{--} else {--}}
                    {{--    $.ajax({--}}
                    {{--        method: "GET",--}}
                    {{--        url: "{{ route('sale_product.details') }}",--}}
                    {{--        data: { serial: serial }--}}
                    {{--    }).done(function( response ) {--}}
                    {{--        // console.log(response);--}}
                    {{--        if (response.success) {--}}
                    {{--            var html = '<tr class="product-item"> <td> <div class="form-group row">' +--}}
                    {{--                ' <input type="hidden" readonly class="form-control purchase_inventory" name="purchase_inventory[]" value="'+response.data.id+'"> ' +--}}
                    {{--                '<input type="text" readonly class="form-control product_serial" name="product_serial[]" value="'+response.data.serial+'"> </div></td><td> ' +--}}
                    {{--                '<div class="form-group row"> <input type="text" readonly class="form-control product_item" name="product_item[]" value="'+response.data.product_item.name+'"> </div></td><td> ' +--}}
                    {{--                '<div class="form-group row"> <input type="text" readonly class="form-control product_category" name="product_category[]" value="'+response.data.product_category.name+'"> </div></td><td> ' +--}}
                    {{--                '<div class="form-group row"> <input type="text" readonly class="form-control warehouse" name="warehouse[]" value="'+response.data.warehouse.name+'"> </div></td><td> <div class="form-group row"> ' +--}}
                    {{--                '<input type="text" readonly class="form-control product_stock" name="product_stock[]" value="'+response.data.quantity+'"> </div></td><td> <div class="form-group row"> <input type="number" class="form-control quantity" name="quantity[]" max="'+response.data.quantity+'" value="6"> </div></td><td>' +--}}
                    {{--                '<div class="form-group row"> <input type="text" class="form-control unit_price" name="unit_price[]" value="'+response.data.selling_price+'"> </div></td><td class="total-cost">Tk 0.00</td><td class="text-center"> <a role="button" class="btn btn-danger btn-sm btn-remove">X</a> </td></tr>';--}}
                    {{--            $('#product-container').append(html);--}}
                    {{--            // console.log(response.data);--}}
                    {{--            serials.push(response.data.serial);--}}
                    {{--            $('.serial').val('');--}}
                    {{--            calculate();--}}
                    {{--        } else {--}}
                    {{--            Swal.fire({--}}
                    {{--                icon: 'error',--}}
                    {{--                title: 'Oops...',--}}
                    {{--                text: 'This product is not available',--}}
                    {{--            });--}}
                    {{--            calculate();--}}
                    {{--        }--}}
                    {{--    });--}}
                    {{--}--}}
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
                        data: { customer_id: customer_id }
                    }).done(function( response ) {
                        if (response) {
                            // console.log(response);
                            var adjust = parseFloat('{{ $order->total }}')-parseFloat('{{ $order->paid }}')
                            $('#previous_due').val(response-adjust);
                            calculate();
                        }
                    });
                }else{
                    $('#previous_due').val(0);
                    calculate();
                }
            });
            $('.customer').trigger('change');

            $('body').on('click', '.btn-remove', function () {
                var index = $('.btn-remove').index(this);
                $(this).closest('.product-item').remove();

                $('.available-quantity:eq('+index+')').closest('tr').remove();
                calculate();
            });

            $('body').on('keyup', '.quantity,.quantity_assign, .unit_price, #transport_cost, #return_amount, #discount_percentage, #vat, #discount, #paid', function () {
                calculate();
            });

            $('body').on('change', '.quantity,.quantity_assign, .unit_price, #transport_cost, #return_amount, #discount_percentage, #previous_due', function () {
                calculate();
            });

            calculate();
        });

        function calculate() {
            var productSubTotal = 0;
            var approvedSubTotal = 0;
            var vat = parseFloat($('#vat').val()||0);
            var discount = parseFloat($('#discount').val()||0);
            var transport_cost = parseFloat($('#transport_cost').val()||0);
            var return_amount = parseFloat($('#return_amount').val()||0);
            var paid = parseFloat($('#paid').val()||0);
            var previous_due = parseFloat($('#previous_due').val()||0);

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var approved_quantity = $('.quantity_assign:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();
                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;
                if (approved_quantity == '' || approved_quantity < 0 || !$.isNumeric(approved_quantity))
                    approved_quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq('+i+')').html('Tk ' + (quantity * unit_price).toFixed(2) );
                productSubTotal += quantity * unit_price;
                approvedSubTotal += parseFloat(approved_quantity);
                //alert(approvedSubTotal);
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

            var total = parseFloat(productSubTotal) + transport_cost + parseFloat(productTotalVat) - parseFloat(discount) - return_amount;

            var due = parseFloat(total) + previous_due - parseFloat(paid);
            $('#final-amount').html('Tk ' + total.toFixed(2));
            $('#final_total').html('Tk ' + (total+previous_due).toFixed(2));
            $('#due').html('Tk ' + due.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));
            $('#approved_total').html(approvedSubTotal);

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
                    text: "Save Sales Order",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save Sales Order!'

                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#sales_order_form').submit();
                    }
                })
            });
        });

    </script>
@endsection
