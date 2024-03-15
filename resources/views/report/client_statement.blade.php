@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Client Report
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
                    <form action="{{ route('report.client_statement') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Sale Type</label>
                                    <select class="form-control select2" name="sale_type" required>
                                        <option value="">Select Option</option>
                                        <option value="1" {{ request()->get('sale_type')=='1'?'selected':'' }}>Retail Sale</option>
                                        <option value="2" {{ request()->get('sale_type')=='2'?'selected':'' }}>Whole Sale</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label> Report type </label>

                                    <select class="form-control select2" name="report_type" required>
                                        <option @if (request()->get('report_type')==1) selected @endif value="1"> Due Customer Report</option>
                                        <option @if (request()->get('report_type')==2) selected @endif value="2"> All Customer Report</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label> Client </label>

                                    <select class="form-control select2" name="customer">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ request()->get('customer') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} - {{ $customer->address }} - {{ $customer->mobile_no }} - {{ $customer->branch->name??'' }}</option>
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
    @if(request()->get('sale_type')==1 || request()->get('sale_type')==2)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                    <div id="prinarea">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center"> Name </th>
                                    <th class="text-center"> Opening Due</th>
                                    <th class="text-center"> Total Quantity </th>
                                    <th class="text-center"> Invoice_Total </th>
                                    @if (request()->get('report_type')!='1')
                                        <th class="text-center"> Party_Discount </th>
                                        <th class="text-center"> Discount_Amount </th>
                                        <th class="text-center"> Return_Amount </th>
                                        <th class="text-center"> Transport_Cost </th>
                                        <th class="text-center"> Sale_Adjustment </th>
                                    @endif
                                    <th class="text-center"> Paid_Amount</th>
                                    <th class="text-center"> Due_Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total = 0;
                                    $opening_due = 0;
                                    $totalQuantity = 0;
                                    $paid = 0;
                                    $discount_amount = 0;
                                    $adjustment_amount = 0;
                                    $return_amount = 0;
                                    $transport_amount = 0;
                                    $partydiscount_amount = 0;
                                    $due = 0;
                                @endphp
                                @foreach($customers as $key => $customer)
                                    @if ($report_type==1 && $customer->due > 0)
                                        @php
                                            $total += $customer->total;
                                            $totalQuantity += $customer->quantity;
                                            $opening_due += $customer->opening_due;
                                            $paid += $customer->paid;
                                            $discount_amount += $customer->discount;
                                            $partydiscount_amount += $customer->partydiscount;
                                            $adjustment_amount += $customer->adjustment;
                                            $return_amount += $customer->return;
                                            $transport_amount += $customer->transport;
                                            $due += $customer->due;
                                        @endphp
                                        <tr>
                                            <td>{{$customer->name}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->opening_due,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->quantity,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->total,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->paid,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->due,2)}}</td>
                                        </tr>
                                    @elseif($report_type==2)
                                        @php
                                            $total += $customer->total;
                                            $totalQuantity += $customer->quantity;
                                            $opening_due += $customer->opening_due;
                                            $paid += $customer->paid;
                                            $partydiscount_amount += $customer->partydiscount;
                                            $discount_amount += $customer->discount;
                                            $return_amount += $customer->return;
                                            $adjustment_amount += $customer->adjustment;
                                            $transport_amount += $customer->transport;
                                            $due += $customer->due;
                                        @endphp
                                        <tr>
                                            <td>{{$customer->name}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->opening_due,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->quantity,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->total,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->partydiscount,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->discount,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->return,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->transport,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->adjustment,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->paid,2)}}</td>
                                            <td class="text-center">Tk  {{number_format($customer->due,2)}}</td>
                                        </tr>
                                    @endif

                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Tk  {{number_format($opening_due,2)}}</th>
                                    <th class="text-center">Tk  {{number_format($totalQuantity,2)}}</th>
                                    <th class="text-center">Tk  {{number_format($total,2)}}</th>
                                    @if (request()->get('report_type')!='1')
                                        <th class="text-center">Tk  {{number_format($partydiscount_amount,2)}}</th>
                                        <th class="text-center">Tk  {{number_format($discount_amount,2)}}</th>
                                        <th class="text-center">Tk  {{number_format($return_amount,2)}}</th>
                                        <th class="text-center">Tk  {{number_format($transport_amount,2)}}</th>
                                        <th class="text-center">Tk  {{number_format($adjustment_amount,2)}}</th>
                                    @endif
                                    <th class="text-center">Tk  {{number_format($paid,2)}}</th>
                                    <th class="text-center">Tk  {{number_format($due,2)}}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


@endsection
@section('script')

    <script>

        $(function (){
            $('#table').DataTable();
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                orientation: 'bottom'
            });

            $('.select2').select2();

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
