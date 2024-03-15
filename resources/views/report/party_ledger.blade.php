@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Party Ledger
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('report.party_ledger') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label>Client</label>
                                    <select class="form-control select2" name="client">
                                        <option value="">Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ request()->get('client') == $client->id ? 'selected' : '' }}>{{ $client->name }} - {{ $client->address }} - {{ $client->mobile_no }} - {{ $client->branch->name??'' }}</option>
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
                        @if($clientName)
                            <h2 class="text-center" style="margin: 0">{{ $clientName->name }}</h2>
                            <h5 class="text-center" style="margin-top: 0"><b>Branch:</b> {{ $clientName->branch->name }}</h5>
                            <h6 class="text-center" style="margin-top: 0"><b>Mobile:</b> {{ $clientName->mobile_no }}</h6>
                        @endif
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th class="text-right">Quantity</th>
                                    <th class="text-right">Invoice</th>
                                    <th class="text-right">Party Discount</th>
                                    <th class="text-right">Discount</th>
                                    <th class="text-right">Transport Cost</th>
                                    <th class="text-right">Return</th>
                                    <th class="text-right">Sale Adjustment</th>
                                    <th class="text-right">Payment</th>
                                    <th class="text-right">Due Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $dueBalance = 0;
                                $totalReturn = 0;
                                $totalPaid = 0;
                                $totalQuantity = 0;
                                $totalInvoice = 0;
                                $totalDiscount = 0;
                                $totalTransportCost = 0;
                                $totalSaleAdjustment = 0;
                                $totalPartyDiscount = 0;
                                usort($clientHistories, function ($a, $b) {
                                    return strtotime($a['date']) - strtotime($b['date']);
                                });
                                ?>
                                @if(count($clientHistories) > 0)
                                    @foreach($clientHistories as $key => $clientHistory)
                                        <?php

                                        $dueBalance += $clientHistory['due_balance'];
                                        $dueBalance -= $clientHistory['payment'];
                                        $dueBalance -= $clientHistory['return'];
                                        $dueBalance -= $clientHistory['party_discount'];
                                        $totalQuantity += $clientHistory['quantity'];
                                        $totalPaid += $clientHistory['payment'];
                                        $totalReturn += $clientHistory['return'];
                                        $totalInvoice += $clientHistory['invoice'];
                                        $totalDiscount += $clientHistory['discount'];
                                        $totalTransportCost += $clientHistory['transport_cost'];
                                        $totalSaleAdjustment += $clientHistory['sale_adjustment'];
                                        $totalPartyDiscount += $clientHistory['party_discount'];

                                        ?>

                                        <tr>
                                            <td>{{$loop->iteration }}</td>
                                            <td>{{ $clientHistory['date'] }}</td>
                                            <td>{{ $clientHistory['particular'] }}</td>
                                            <td class="text-right">{{ $clientHistory['quantity'] > 0 ? number_format($clientHistory['quantity'],2) : '' }}</td>
                                            <td class="text-right">{{ $clientHistory['invoice'] > 0 ? number_format($clientHistory['invoice'],2) : '' }}</td>
                                            <td class="text-right">{{ $clientHistory['party_discount'] > 0 ? number_format($clientHistory['party_discount'],2) : '' }}</td>
                                            <td class="text-right">{{ $clientHistory['discount'] > 0 ? number_format($clientHistory['discount'],2) : '' }}</td>
                                            <td class="text-right">{{ $clientHistory['transport_cost'] > 0 ? number_format($clientHistory['transport_cost'],2) : '' }}</td>
                                            <td class="text-right">{{ $clientHistory['return'] > 0 ? number_format($clientHistory['return'],2) : '' }}</td>
                                            <td class="text-right">{{ $clientHistory['sale_adjustment'] > 0 ? number_format($clientHistory['sale_adjustment'],2) : '' }}</td>
                                            <td class="text-right">{{ $clientHistory['payment'] > 0 ? number_format($clientHistory['payment'],2) : ''  }}</td>
                                            <td class="text-right">{{ number_format($dueBalance,2) }}</td>
                                        </tr>
                                @endforeach
                                @endif
                                <tbody>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th class="text-right">{{ number_format($totalQuantity,2) }}</th>
                                    <th class="text-right">{{ number_format($totalInvoice,2) }}</th>
                                    <th class="text-right">{{ number_format($totalPartyDiscount,2) }}</th>
                                    <th class="text-right">{{ number_format($totalDiscount,2) }}</th>
                                    <th class="text-right">{{ number_format($totalTransportCost,2) }}</th>
                                    <th class="text-right">{{ number_format($totalReturn,2) }}</th>
                                    <th class="text-right">{{ number_format($totalSaleAdjustment,2) }}</th>
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
