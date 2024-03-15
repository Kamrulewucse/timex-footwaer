@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Profit & Loss
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
                    <form action="{{ route('report.profit_and_loss') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label>Sale Type</label>
                                    <select class="form-control select2" name="sale_type" required>
                                         <option value="">Select Option</option>
                                         <option value="1" {{ request()->get('sale_type')=='1'?'selected':'' }}>Retail Sale</option>
                                         <option value="2" {{ request()->get('sale_type')=='2'?'selected':'' }}>Whole Sale</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')??date('Y-m-d')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label>End Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="end" name="end" value="{{ request()->get('end')??date('Y-m-d')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
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

    <div class="row">
        <div class="col-sm-12" style="min-height:300px">
            @if($incomes)
                <section class="card">

                    <div class="card-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
                            <h4 class="text-center"><b>Profit & Loss Report</b></h4>
                            @if($incomes)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header with-border">
                                                <h3 class="card-title">Summary</h3>
                                            </div>
                                            <!-- /.box-header -->

                                            <div class="card-body">
                                                @if(($incomes->sum('amount')) >= $expenses->sum('amount'))
                                                    <strong>Total Revenue : </strong> Tk  {{ number_format($incomes->sum('amount'),2)}}<br>
                                                    <strong>Total Expense : </strong> Tk  {{ number_format($expenses->sum('amount'),2) }}<br>
                                                    <strong>Net Profit : </strong> Tk  {{ number_format(($incomes->sum('amount')) - $expenses->sum('amount'), 2) }}
                                                @else
                                                    <strong>Loss: </strong> {{ number_format($expenses->sum('amount') - ($incomes->sum('amount')), 2) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div style="clear: both; width: 100% !important" class="table-responsive">
                                <table class="table table-bordered " style="margin-bottom: 0px;">
                                    <tr>
                                        <th class="text-center">{{ date("F d, Y", strtotime(request()->get('start'))).' - '.date("F d, Y", strtotime(request()->get('end'))) }}</th>
                                    </tr>
                                    <table class="table table-bordered" style="width:50%; float:left">
                                        <tr>
                                            <th colspan="3" class="text-center">Income(Credit)</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" colspan="2">Total</th>
                                            <th class="text-center">Tk  {{ number_format($incomes->sum('amount'),2)}}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Particular</th>
                                            {{--                                        <th class="text-center">Note</th>--}}
                                            {{--                                        <th class="text-center">Payment Type</th>--}}
                                            {{--                                        <th class="text-center">Bank Details</th>--}}
                                            <th class="text-center">Amount</th>
                                        </tr>
                                        @foreach($incomes as $income)
                                            <tr>
                                                <td class="text-center">{{ $income->date->format('j F, Y') }}</td>
                                                <td>{{ $income->particular }} ({{$income->companyBranch->name ?? ''}})</td>
                                                {{--                                            <td class="text-center">{{ $income->note }}</td>--}}
                                                {{--                                            <td class="text-center">--}}
                                                {{--                                                @if($income->transaction_method == 1)--}}
                                                {{--                                                    Cash--}}
                                                {{--                                                @elseif($income->transaction_method == 2)--}}
                                                {{--                                                    Bank--}}
                                                {{--                                                @elseif($income->transaction_method == 3)--}}
                                                {{--                                                    Mobile Banking--}}
                                                {{--                                                @endif--}}
                                                {{--                                            </td>--}}
                                                {{--                                            <td class="text-center">--}}
                                                {{--                                                --}}{{--                                                @if ($income->transaction_method == 2)--}}
                                                {{--                                                --}}{{--                                                    {{ $income->bank->name??''.' - '.$income->account->account_no??'' }}--}}
                                                {{--                                                --}}{{--                                                @endif--}}
                                                {{--                                            </td>--}}
                                                <td class="text-center">Tk  {{ number_format($income->amount),2 }}</td>
                                            </tr>
                                        @endforeach

                                        <?php
                                        $incomesCount = count($incomes);
                                        $expensesCount = count($expenses);

                                        if ($incomesCount > $expensesCount)
                                            $maxCount = $incomesCount;
                                        else
                                            $maxCount = $expensesCount;

                                        $maxCount += 2;
                                        ?>
                                        @for($i=count($incomes); $i<$maxCount; $i++)
                                            <tr>
                                                <td><br><br></td>
                                                <td></td>
                                                {{--                                            <td></td>--}}
                                                {{--                                            <td></td>--}}
                                                {{--                                            <td></td>--}}
                                                <td></td>
                                            </tr>
                                        @endfor

                                    </table>
                                    <table id="table" class="table table-bordered table-striped" style="width:50%; float:left">
                                        <tr>
                                            <th colspan="5" class="text-center">Expense(Debit)</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" colspan="2">Total</th>
                                            <th class="text-center">Tk  {{ number_format($expenses->sum('amount'),2) }}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Particular</th>
                                            {{--                                        <th class="text-center">Note</th>--}}
                                            {{--                                        <th class="text-center">Payment Type</th>--}}
                                            {{--                                        <th class="text-center">Bank Details</th>--}}
                                            <th class="text-center">Amount</th>
                                        </tr>


                                        @foreach($expenses as $expense)
                                            <tr>
                                                <td class="text-center">{{ $expense->date->format('j F, Y') }}</td>
                                                <td>{{ $expense->particular }} ({{$expense->companyBranch->name ?? ''}})</td>
                                                {{--                                            <td class="text-center">{{ $expense->note }}</td>--}}
                                                {{--                                            <td class="text-center">--}}
                                                {{--                                                @if($expense->transaction_method == 1)--}}
                                                {{--                                                    Cash--}}
                                                {{--                                                @elseif($expense->transaction_method == 2)--}}
                                                {{--                                                    Bank--}}
                                                {{--                                                @elseif($expense->transaction_method == 3)--}}
                                                {{--                                                    Mobile Banking--}}
                                                {{--                                                @endif--}}
                                                {{--                                            </td>--}}
                                                {{--                                            <td class="text-center">--}}
                                                {{--                                                @if ($expense->transaction_method == 2)--}}
                                                {{--                                                    {{ $expense->bank->name.' - '.$expense->account->account_no }}--}}
                                                {{--                                                @endif--}}
                                                {{--                                            </td>--}}
                                                <td class="text-center">Tk  {{ number_format($expense->amount,2) }}</td>
                                            </tr>
                                        @endforeach

                                        @for($i=count($expenses); $i<$maxCount; $i++)
                                            <tr>
                                                <td><br><br></td>
                                                <td></td>
                                                {{--                                            <td></td>--}}
                                                {{--                                            <td></td>--}}
                                                {{--                                            <td></td>--}}
                                                <td></td>
                                            </tr>
                                        @endfor

                                    </table>
                                </table>
                            </div>
                            {{--                            <div class="row">--}}

                            {{--                            </div>--}}

                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
