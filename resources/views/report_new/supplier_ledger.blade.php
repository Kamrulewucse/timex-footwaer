@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Supplier Ledger
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('report.supplier_ledger') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label>Supplier</label>
                                    <select class="form-control select2" name="supplier">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request()->get('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }} - {{ $supplier->address }} - {{ $supplier->mobile_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')??date('Y-m-d')  }}" autocomplete="off">
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
                                               id="end" name="end" value="{{ request()->get('end')??date('Y-m-d')  }}" autocomplete="off">
                                    </div>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>
                    <div id="prinarea">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2 style="margin-bottom: 0px;">Megha Footwear</h2>
                                <h6 style="margin-bottom: 0px;">Megha trading, 174, Siddik bazar Dhaka - 1000</h6>
                                <h6 style="margin-bottom: 0px;">Hotline: 01841509263 Phone: 02226638333, 01720009263</h6>
                                <h6 style="margin-bottom: 0px;">Bin No: 001067154-0205</h6>
                                <h5 style="margin-bottom: 0px;margin-top: 0px;padding: 0">@if(request()->get('start')) Date:
                                    {{ date('d-m-Y',strtotime(request()->get('start'))) }} to {{ date('d-m-Y',strtotime(request()->get('end'))) }} @endif</h5>
                            </div>
                        </div>
                        <hr>
                        <h2 class="text-center" style="margin-top: -15 !important;">Supplier Ledger</h2>
                        @if($supplierName)
                            <h6 style="margin-top: 0"><b>Name &nbsp; :</b> {{ $supplierName->name }}</h6>
                            <h6 style="margin-top: 0"><b>Address:</b> {{ $supplierName->address }}</h6>
                            <h6 style="margin-top: 0"><b>Mobile:</b> {{ $supplierName->mobile_no }}</h6>
                        @endif
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th class="text-right">Quantity</th>
                                    <th class="text-right">Invoice Total</th>
                                    <th class="text-right">Discount</th>
                                    <th class="text-right">Transport Cost</th>
                                    <th class="text-right">Payment</th>
                                    <th class="text-right">Due Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $dueBalance = 0;
                                $totalPaid = 0;
                                $totalQuantity = 0;
                                $totalInvoice = 0;
                                $totalDiscount = 0;
                                $totalTransportCost = 0;
                                usort($supplierHistories, function ($a, $b) {
                                    return strtotime($a['date']) - strtotime($b['date']);
                                });
                                ?>
                                @if(count($supplierHistories) > 0)
                                    @foreach($supplierHistories as $key => $supplierHistory)
                                        <?php

                                        $dueBalance += $supplierHistory['due_balance'];
                                        $dueBalance -= $supplierHistory['payment'];
                                        $totalQuantity += $supplierHistory['quantity'];
                                        $totalPaid += $supplierHistory['payment'];
                                        $totalInvoice += $supplierHistory['invoice'];
                                        $totalDiscount += $supplierHistory['discount'];
                                        $totalTransportCost += $supplierHistory['transport_cost'];

                                        ?>

                                        <tr>
                                            <td>{{$loop->iteration }}</td>
                                            <td>{{ $supplierHistory['date'] }}</td>
                                            <td>{{ $supplierHistory['particular'] }}</td>
                                            <td class="text-right">{{ $supplierHistory['quantity'] > 0 ? number_format($supplierHistory['quantity'],2) : '' }}</td>
                                            <td class="text-right">{{ $supplierHistory['invoice'] > 0 ? number_format($supplierHistory['invoice'],2) : '' }}</td>
                                            <td class="text-right">{{ $supplierHistory['discount'] > 0 ? number_format($supplierHistory['discount'],2) : '' }}</td>
                                            <td class="text-right">{{ $supplierHistory['transport_cost'] > 0 ? number_format($supplierHistory['transport_cost'],2) : '' }}</td>
                                            <td class="text-right">{{ $supplierHistory['payment'] > 0 ? number_format($supplierHistory['payment'],2) : ''  }}</td>
                                            <td class="text-right">{{ number_format($dueBalance,2) }}</td>
                                        </tr>
                                @endforeach
                                @endif
                                <tbody>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th class="text-right">{{ number_format($totalQuantity,2) }}</th>
                                    <th class="text-right">{{ number_format($totalInvoice,2) }}</th>
                                    <th class="text-right">{{ number_format($totalDiscount,2) }}</th>
                                    <th class="text-right">{{ number_format($totalTransportCost,2) }}</th>
                                    <th class="text-right">{{ number_format($totalPaid,2) }}</th>
                                    <th class="text-right">{{ number_format($dueBalance,2) }}</th>
                                </tr>
                                </tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
            $('#table').DataTable();
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                orientation: 'bottom'
            });

        });
        $('.select2').select2();

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {
            var clonedTable = $('#'+print).clone();
            clonedTable.find('table').DataTable().destroy();
            clonedTable.find('.dataTables_info, .dataTables_paginate, .dataTables_length, .dataTables_filter').remove();
            $('body').html(clonedTable);
            window.print();
            window.location.replace(APP_URL);
        }
    </script>
@endsection
