@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Cashbook
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
                    <form action="{{ route('report.cashbook') }}">
                        <div class="row">
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
            @if($result)
                <section class="card">

                    <div class="card-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
                            <div class="row">
                                <div class="col-xs-12">
                                    {{--                                    @if (Auth::user()->company_branch_id == 2)--}}
                                    {{--                                        <img src="{{ asset('img/your_choice_plus.png') }}"style="margin-top: 10px; float:inherit">--}}
                                    {{--                                    @else--}}
                                    {{--                                        <img src="{{ asset('img/your_choice.png') }}"style="margin-top: 10px; float:inherit">--}}
                                    {{--                                    @endif--}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h2 style="margin-bottom: 0px;">Megha Footwear</h2>
                                    <h6 style="margin-bottom: 0px;">Megha trading, 174, Siddik bazar Dhaka - 1000</h6>
                                    <h6 style="margin-bottom: 0px;">Hotline: 01841509263 Phone: 02226638333, 01720009263</h6>
                                    <h6 style="margin-bottom: 0px;">Bin No: 001067154-0205</h6>
                                    <h4 style="margin-bottom: 0px;margin-top: 0px;padding: 0">Cashbook Report</h4>
                                </div>
                            </div>

                            {{--                            <div class="table-responsive">--}}
                            {{--                            <table class="table table-bordered" style="margin-bottom: 0px">--}}
                            {{--                                <tr>--}}
                            {{--                                    <th>Opening Balance</th>--}}
                            {{--                                    <th class="text-center">{{ number_format($openingBalance,2) }}</th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                    <th></th>--}}
                            {{--                                </tr>--}}
                            {{--                            </table>--}}
                            {{--                            </div>--}}
                            @php
                                $totalIncome = 0;
                                $totalExpense = 0;
                            @endphp
                            @foreach($result as $item)
                                <table class="table table-bordered" style="margin-bottom: 0px">
                                    <tr>
                                        <th class="text-center">{{ date("F d, Y", strtotime($item['date'])) }}</th>
                                    </tr>
                                </table>

                                <div style="clear: both" class="table-responsive">
                                    <table class="table table-bordered" style="width:50%; float:left">
                                        <tr>
                                            <th colspan="5" class="text-center">Income(Credit)</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" width="30%">Particular</th>
                                            <th class="text-center" width="20%">Note</th>
                                            <th class="text-center" width="15%">Payment Type</th>
                                            <th class="text-center" width="20%">Bank Details</th>
                                            <th class="text-center" width="15%">Amount</th>
                                        </tr>

                                        @foreach($item['incomes'] as $income)

                                            <tr>
                                                <td class="text-center">
                                                    @if($income->particular=='Balance Transfer')
                                                        @php
                                                            $transfer=\App\Model\BalanceTransfer::find($income->balance_transfer_id);
                                                        @endphp
                                                        {{ $income->particular}}-
                                                        @if($transfer->target_bank_account_id ?? '')
                                                            {{$transfer->targetBankAccount->bank->name}} (AC : {{$transfer->targetBankAccount->account_no ?? ''}})
                                                        @else
                                                            {{$transfer->targetBranch->name ?? 'Admin Cash' }}
                                                        @endif
                                                    @else
                                                        {{$income->particular}}
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $income->note }}</td>
                                                <td class="text-center">{{ $income->transaction_method == 1 ? 'Cash' : 'Bank' }}</td>
                                                <td class="text-center">
                                                    @if ($income->transaction_method == 2)
                                                        {{ $income->bank->name??''.' - '.$income->account->account_no??'' }}
                                                    @endif
                                                </td>
                                                <td class="text-center">Tk  {{ number_format($income->amount ,2) }}</td>
                                            </tr>
                                        @endforeach

                                        <?php
                                        $incomesCount = count($item['incomes']);
                                        $expensesCount = count($item['expenses']);

                                        if ($incomesCount > $expensesCount)
                                            $maxCount = $incomesCount;
                                        else
                                            $maxCount = $expensesCount;
                                        $maxCount += 2;
                                        ?>

                                        @for($i=count($item['incomes']); $i<$maxCount; $i++)
                                            <tr>
                                                <td><br><br></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endfor

                                        <tr>
                                            <th class="text-center" colspan="4">Total</th>
                                            <th class="text-center">Tk  {{ number_format($item['incomes']->sum('amount') ,2) }}</th>
                                            @php
                                                $totalIncome += $item['incomes']->sum('amount');
                                            @endphp
                                        </tr>
                                    </table>
                                    <table class="table table-bordered" style="width:50%; float:left">
                                        <tr>
                                            <th colspan="5" class="text-center">Expense(Debit)</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" width="30%">Particular</th>
                                            <th class="text-center" width="20%">Note</th>
                                            <th class="text-center" width="15%">Payment Type</th>
                                            <th class="text-center" width="20%">Bank Details</th>
                                            <th class="text-center" width="15%">Amount</th>
                                        </tr>

                                        @foreach($item['expenses'] as $expense)
                                            <tr>
                                                <td>
                                                    @if($expense->particular=='Balance Transfer')
                                                        @php
                                                            $transfer=\App\Model\BalanceTransfer::find($expense->balance_transfer_id);
                                                        @endphp
                                                        {{ $expense->particular}}-
                                                        @if($transfer->source_bank_account_id ?? '')
                                                            {{$transfer->sourceBankAccount->bank->name ?? ''}} (AC : {{$transfer->sourceBankAccount->account_no ?? ''}})
                                                        @else
                                                            {{$transfer->sourchBranch->name ?? 'Admin Cash' }}
                                                        @endif
                                                    @else
                                                        {{$expense->particular}}
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $expense->note }}</td>
                                                <td class="text-center">{{ $expense->transaction_method == 1 ? 'Cash' : 'Bank' }}</td>
                                                <td class="text-center">
                                                    @if($expense->transaction_method == 2)
                                                        {{ $expense->bank->name??''.' - '.$expense->account->account_no??'' }}
                                                    @endif
                                                </td>
                                                <td class="text-center">Tk  {{ number_format($expense->amount,2) }}</td>
                                            </tr>
                                        @endforeach

                                        @for($i=count($item['expenses']); $i<$maxCount; $i++)
                                            <tr>
                                                <td><br><br></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endfor

                                        <tr>
                                            <th class="text-center" colspan="4">Total</th>
                                            <th class="text-center">Tk  {{ number_format($item['expenses']->sum('amount'),2) }}</th>
                                        </tr>
                                        @php
                                            $totalExpense += $item['expenses']->sum('amount');
                                        @endphp
                                    </table>
                                </div>
                            @endforeach
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin-bottom: 0px; width:50%; float:left">
                                    <tr>
                                        <th>Previous Balance</th>
                                        <th class="text-center">{{ number_format($openingBalance,2) }}</th>
                                    </tr>
                                    <tr>
                                        <th>Total Balance</th>
                                        <th class="text-center">{{ number_format($openingBalance + ($totalIncome),2) }}</th>
                                    </tr>
                                    <tr>
                                        <th>Cash Expense</th>
                                        <th class="text-center">{{ number_format($totalExpense,2) }}</th>
                                    </tr>
                                    <tr>
                                        <th>Cash In Hand</th>
                                        <th class="text-center">{{ number_format($openingBalance + (($totalIncome) - $totalExpense),2) }}</th>
                                    </tr>
                                </table>
                            </div>
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
