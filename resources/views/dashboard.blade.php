@extends('layouts.app')
@section('title','Dashboard')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
      .info-box-content{
            font-size: 0.8rem !important;
            font-weight:1000 !important;
        }
        img{
            border-radius: 25px;
            margin-top: -20px;
            margin-bottom: 26px;
        }
      [class*=sidebar-dark] .brand-link, [class*=sidebar-dark] .brand-link .pushmenu {
          height: 45px !important;
          padding-top: 16px !important;
      }
        /*.payment{*/
        /*    margin-bottom: 25px;*/
        /*    margin-top: -26px;*/
        /*    float: right;*/
        /*    margin-right: 41px;*/
        /*}*/
    </style>
@endsection

@section('content')

    @if(Auth::user()->id != 36)
        <?php
//            if(Auth::user()->company_branch_id != 0){
                $todayInvoiceTotal = \App\Model\SalesOrder::where('company_branch_id', Auth::user()->company_branch_id)->whereDate('date', date('Y-m-d'))->sum('invoice_total');
//            }
                $totalDueCollection = $todayCashSale-$todayInvoiceTotal>0 ? $todayCashSale-$todayInvoiceTotal : 0;

        ?>
{{--        @if(auth()->user()->role==0)--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-3 col-6">--}}
{{--                    <a href="{{ route('dashboard',['branch'=>0]) }}">--}}
{{--                        <div class="small-box bg-gradient-indigo">--}}
{{--                            <div class="inner">--}}
{{--                                <h5 style="color: white !important;">{{ 'Admin' }}</h5>--}}
{{--                                <!--<br/><br/>-->--}}
{{--                            </div>--}}
{{--                            <div class="icon">--}}
{{--                                <i class="fas fa-receipt"></i>--}}
{{--                            </div>--}}
{{--                            <a href="{{ route('dashboard',['branch'=>0]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                @foreach($companyBranches as $index => $companyBranch)--}}
{{--                    <div class="col-lg-3 col-6">--}}
{{--                        <a href="{{ route('dashboard',['branch'=>$companyBranch->id]) }}">--}}
{{--                            <div class="small-box {{$index == 0?'bg-gradient-red':''}}{{ $index == 1?'bg-gradient-blue':'' }}{{ $index == 2?'bg-gradient-info':'' }}">--}}
{{--                                <div class="inner">--}}
{{--                                    <h5 style="color: white !important;">{{ $companyBranch->name }}</h5>--}}
{{--                                    <!--<br/><br/>-->--}}
{{--                                </div>--}}
{{--                                <div class="icon">--}}
{{--                                    <i class="fas fa-receipt"></i>--}}
{{--                                </div>--}}
{{--                                <a href="{{ route('dashboard',['branch'=>$companyBranch->id]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        @endif--}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info"><i class="far fa-heart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Sale</span>
                        <span class="info-box-number">Tk {{ number_format($todaySale , 2) }}</span>
                    </div>

                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Cash Sale</span>
                        <span class="info-box-number">Tk {{ number_format($todayCashSale-$totalDueCollection, 2) }}</span>
                    </div>
                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-dark"><i class="far fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Due</span>
                        <span class="info-box-number">Tk {{ number_format($todayDue-$todaySaleAdjustment , 2) }}</span>
                    </div>
                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-lg">
                    <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Due Collection</span>
                        <span class="info-box-number">Tk {{ number_format( $totalDueCollection+$todayDueCollection  , 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info" style="background-color: #1E3050 !important"><i class="far fa-copy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Expense</span>
                        <span class="info-box-number">Tk {{ number_format($todayExpense, 2) }}</span>
                    </div>
                </div>
            </div>
{{--            @if(auth()->user()->company_branch_id==0)--}}
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon bg-info" style="background-color: #105bd6 !important"><i class="fas fa-brush"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today's Pair Sale</span>
                            <span class="info-box-number">{{ number_format($todayPairSale, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon bg-info" style="background-color: #9c4e61 !important"><i class="fas fa-paint-roller"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"> Total Stock Quantity</span>
                            <span class="info-box-number">{{ number_format($totalStock, 2) }}</span>
                        </div>
                    </div>
                </div>
{{--                <div class="col-md-3 col-sm-6 col-12">--}}
{{--                    <div class="info-box shadow-none">--}}
{{--                        <span class="info-box-icon bg-info" style="background-color: #685208 !important"><i class="fas fa-fill"></i></span>--}}
{{--                        <div class="info-box-content">--}}
{{--                            <span class="info-box-text"> Total Stock Value</span>--}}
{{--                            <span class="info-box-number">Tk {{ number_format($totalStockValue, 2) }}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon bg-info" style="background-color: #a94822 !important"><i class="fas fa-money-bill"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"> Today's Profit & Loss</span>
                            <span class="info-box-number">Tk {{ number_format($todayProfitLoss, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-none">
                        <span class="info-box-icon bg-info" style="background-color: #0a704f !important"><i class="fas fa-credit-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"> Cash In Hand</span>
                            <span class="info-box-number">Tk {{ number_format($cashInHand, 2) }}</span>
                        </div>
                    </div>
                </div>
{{--                <div class="col-md-6 col-sm-6 col-12">--}}
{{--                    <div class="info-box shadow-none">--}}
{{--                        <span class="info-box-icon bg-info" style="background-color: #71348d !important"><i class="fas fa-money-check"></i></span>--}}
{{--                        <div class="info-box-content">--}}
{{--                            <span class="info-box-text"> Today's  Received Amount by Bank</span>--}}
{{--                            <span class="info-box-number">Tk {{ number_format($receivedByBank, 2) }}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--            @if(auth()->user()->company_branch_id!=0)--}}
{{--                <div class="col-md-3 col-sm-6 col-12">--}}
{{--                    <div class="info-box shadow-none">--}}
{{--                        <span class="info-box-icon bg-info" style="background-color: #0a704f !important"><i class="fas fa-credit-card"></i></span>--}}
{{--                        <div class="info-box-content">--}}
{{--                            <span class="info-box-text"> Cash In Hand</span>--}}
{{--                            <span class="info-box-number">Tk {{ number_format($cashInHand, 2) }}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
        <h5 class="mb-2 text-center"><b style="color: red;">Retail Sale Info</b></h5>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info"><i class="far fa-heart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Sale</span>
                        <span class="info-box-number">Tk {{ number_format(0 , 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Cash Sale</span>
                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>
                    </div>
                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-dark"><i class="far fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Due</span>
                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>
                    </div>
                </div>
            </div>

{{--            <div class="col-md-3 col-sm-6 col-12">--}}
{{--                <div class="info-box shadow-lg">--}}
{{--                    <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>--}}
{{--                    <div class="info-box-content">--}}
{{--                        <span class="info-box-text">Today's Due Collect</span>--}}
{{--                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-3 col-sm-6 col-12">--}}
{{--                <div class="info-box shadow-none">--}}
{{--                    <span class="info-box-icon bg-info" style="background-color: #1E3050 !important"><i class="far fa-copy"></i></span>--}}
{{--                    <div class="info-box-content">--}}
{{--                        <span class="info-box-text">Today's Expense</span>--}}
{{--                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-3 col-sm-6 col-12">--}}
{{--                <div class="info-box shadow-none">--}}
{{--                    <span class="info-box-icon bg-info" style="background-color: #0a704f !important"><i class="fas fa-credit-card"></i></span>--}}
{{--                    <div class="info-box-content">--}}
{{--                        <span class="info-box-text"> Cash In Hand</span>--}}
{{--                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
        <h5 class="mb-2 text-center"><b style="color: red;">WholeSale Info</b></h5>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info"><i class="far fa-heart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Sale</span>
                        <span class="info-box-number">Tk {{ number_format(0 , 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Cash Sale</span>
                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>
                    </div>
                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-dark"><i class="far fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Due</span>
                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>
                    </div>
                </div>
            </div>

{{--            <div class="col-md-3 col-sm-6 col-12">--}}
{{--                <div class="info-box shadow-lg">--}}
{{--                    <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>--}}
{{--                    <div class="info-box-content">--}}
{{--                        <span class="info-box-text">Today's Due Collect</span>--}}
{{--                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-3 col-sm-6 col-12">--}}
{{--                <div class="info-box shadow-none">--}}
{{--                    <span class="info-box-icon bg-info" style="background-color: #1E3050 !important"><i class="far fa-copy"></i></span>--}}
{{--                    <div class="info-box-content">--}}
{{--                        <span class="info-box-text">Today's Expense</span>--}}
{{--                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-3 col-sm-6 col-12">--}}
{{--                <div class="info-box shadow-none">--}}
{{--                    <span class="info-box-icon bg-info" style="background-color: #0a704f !important"><i class="fas fa-credit-card"></i></span>--}}
{{--                    <div class="info-box-content">--}}
{{--                        <span class="info-box-text"> Cash In Hand</span>--}}
{{--                        <span class="info-box-number">Tk {{ number_format(0, 2) }}</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

        <!--@if(auth()->user()->company_branch_id == 0)
        @foreach($companyBranches as $companyBranch)
            <?php
//                $todaySale = \App\Model\SalesOrder::where('company_branch_id', $companyBranch->id)->whereDate('date', date('Y-m-d'))->sum('total');
//                $todayInvoiceTotal = \App\Model\SalesOrder::where('company_branch_id', $companyBranch->id)->whereDate('date', date('Y-m-d'))->sum('invoice_total');
//                $todayDue = \App\Model\SalesOrder::where('company_branch_id', $companyBranch->id)->whereDate('date', date('Y-m-d'))->sum('due');
//                $todaySaleAdjustment = \App\Model\SalesOrder::where('company_branch_id', $companyBranch->id)->whereDate('date', date('Y-m-d'))->sum('sale_adjustment');
//                $cashInHand = \App\Model\BranchCash::where('company_branch_id',$companyBranch->id)->first()->amount;
//                $todayDueCollection = \App\Model\SalePayment::whereDate('date', date('Y-m-d'))
//                ->where('company_branch_id', $companyBranch->id)
//                ->where('type', 1)
//                ->where('received_type', 2)
//                ->whereNotIn('transaction_method', [4,5])
//                ->sum('amount');
//                $todayCashSale = \App\Model\SalePayment::where('company_branch_id', $companyBranch->id)->whereDate('date', date('Y-m-d'))
//                    ->where('type', 1)
//                    ->where('received_type', 1)->sum('amount');
//                $todayExpense = \App\Model\TransactionLog::where('company_branch_id', $companyBranch->id)->whereDate('date', date('Y-m-d'))
//                    ->whereIn('transaction_type', [3, 2, 6])
//                    ->whereNotIn('transaction_method', [4,5])
//                    ->whereIn('balance_transfer_id', [null])
//                    ->sum('amount');
//                $totalDueCollection = $todayCashSale-$todayInvoiceTotal>0 ? $todayCashSale-$todayInvoiceTotal : 0;
            ?>
            <h5 class="mb-2 text-center"><b>{{$companyBranch->name ?? ''}} Info</b></h5>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info"><i class="far fa-heart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Sale</span>
                        <span class="info-box-number">Tk {{ number_format($todaySale , 2) }}</span>
                    </div>

                </div>

            </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today's Cash Sale</span>
                            <span class="info-box-number">Tk {{ number_format($todayCashSale-$totalDueCollection, 2) }}</span>
                        </div>
                    </div>

                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-warning"><i class="far fa-calendar-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today's Due</span>
                            <span class="info-box-number">Tk {{ number_format($todayDue-$todaySaleAdjustment , 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-lg">
                        <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today's Due Collect</span>
                            <span class="info-box-number">Tk {{ number_format( $totalDueCollection+$todayDueCollection  , 2) }}</span>
                        </div>
                    </div>
                </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info" style="background-color: #1E3050 !important"><i class="far fa-copy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Expense</span>
                        <span class="info-box-number">Tk {{ number_format($todayExpense , 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info" style="background-color: #0a704f !important"><i class="fas fa-credit-card"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"> Cash In Hand</span>
                        <span class="info-box-number">Tk {{ number_format($cashInHand, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        -->
    @endif
@endsection

@section('script')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#table_id').DataTable({
                ordering: false
            });

            $('#p_table').DataTable({ordering: false});
        } );
    </script>

@endsection
