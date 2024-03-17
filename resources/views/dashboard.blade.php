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
      .info-box {
          background-color: #cf0dfb;
          color: #f7efef;
      }
      .retail-info-box {
          background-color: #0810b3;
          color: #f7efef;
      }
      .wholesale-info-box {
          background-color: #0c7048;
          color: #f7efef;
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
        <?php
                $todayInvoiceTotal = \App\Model\SalesOrder::whereDate('date', date('Y-m-d'))->sum('invoice_total');
                $totalDueCollection = $todayCashSale-$todayInvoiceTotal>0 ? $todayCashSale-$todayInvoiceTotal : 0;
                $todayDueCollection = \App\Model\SalePayment::whereDate('date', date('Y-m-d'))
                ->where('type', 1)
                ->where('received_type', 2)
                ->whereNotIn('transaction_method', [4,5])
                ->sum('amount');
        ?>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info"><i class="far fa-heart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের বিক্রয়</span>
                        <span class="info-box-number">Tk {{ number_format($todaySale , 2) }}</span>
                    </div>

                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের ক্যাশ বিক্রয়</span>
                        <span class="info-box-number">Tk {{ number_format($todayCashSale-$totalDueCollection, 2) }}</span>
                    </div>
                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-dark"><i class="far fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের বকেয়া</span>
                        <span class="info-box-number">Tk {{ number_format($todayDue-$todaySaleAdjustment , 2) }}</span>
                    </div>
                </div>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-lg">
                    <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের বকেয়া সংগ্রহ</span>
                        <span class="info-box-number">Tk {{ number_format( $totalDueCollection+$todayDueCollection  , 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info" style="background-color: #1E3050 !important"><i class="far fa-copy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের ব্যয়</span>
                        <span class="info-box-number">Tk {{ number_format($todayExpense, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info" style="background-color: #105bd6 !important"><i class="fas fa-brush"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের পেয়ার সেল</span>
                        <span class="info-box-number">{{ number_format($todayPairSale, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info" style="background-color: #9c4e61 !important"><i class="fas fa-paint-roller"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"> মোট স্টকের পরিমাণ</span>
                        <span class="info-box-number">{{ number_format($totalStock, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info" style="background-color: #a94822 !important"><i class="fas fa-money-bill"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের লাভ & লস</span>
                        <span class="info-box-number">Tk {{ number_format($todayProfitLoss, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info" style="background-color: #0a704f !important"><i class="fas fa-credit-card"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">হাতে ক্যাশ</span>
                        <span class="info-box-number">Tk {{ number_format($cashInHand, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $todayRetailSale = \App\Model\SalesOrder::where('type',1)->whereDate('date', date('Y-m-d'))->sum('total');
        $todayRetailCashSale = \App\Model\SalePayment::where('sale_type_status',1)->whereDate('date', date('Y-m-d'))
            ->where('type', 1)
            ->where('received_type', 1)->sum('amount');
        $todayRetailDue = \App\Model\SalesOrder::where('type',1)->whereDate('date', date('Y-m-d'))->sum('due');
        $todayRetailInvoiceTotal = \App\Model\SalesOrder::where('type',1)->whereDate('date', date('Y-m-d'))->sum('invoice_total');
        $totalRetailDueCollection = $todayRetailCashSale-$todayRetailInvoiceTotal>0 ? $todayRetailCashSale-$todayRetailInvoiceTotal : 0;
        $todayRetailDueCollection = \App\Model\SalePayment::where('sale_type_status',1)->where('type',1)->whereDate('date', date('Y-m-d'))
            ->where('type', 1)
            ->where('received_type', 2)
            ->whereNotIn('transaction_method', [4,5])
            ->sum('amount');

        $retail_incomes = \App\Model\TransactionLog::where('sale_type_status',1)->where('transaction_type', 1)->whereIn('net_profit', [1, 2])->whereDate('date', date('Y-m-d'))->get()->sum('amount');
        $retail_expenses = \App\Model\TransactionLog::where('sale_type_status',1)->whereIn('transaction_type', [4, 2])->whereIn('balance_transfer_id', [null])->whereDate('date', date('Y-m-d'))->get()->sum('amount');
        $todayRetailProfitLoss = $retail_incomes-$retail_expenses;
        ?>
        <h5 class="mb-2 text-center"><b style="color: #38f046;">খুচরা বিক্রয় তথ্য</b></h5>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box retail-info-box shadow-none">
                    <span class="info-box-icon bg-info"><i class="far fa-heart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের বিক্রয়</span>
                        <span class="info-box-number">Tk {{ number_format($todayRetailSale , 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box retail-info-box shadow-sm">
                    <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের ক্যাশ বিক্রয়</span>
                        <span class="info-box-number">Tk {{ number_format($todayRetailCashSale-$totalRetailDueCollection, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box retail-info-box shadow">
                    <span class="info-box-icon bg-dark"><i class="far fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের বকেয়া</span>
                        <span class="info-box-number">Tk {{ number_format($todayRetailDue-$totalRetailDueCollection, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box retail-info-box shadow-lg">
                    <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের লাভ & লস</span>
                        <span class="info-box-number">Tk {{ number_format($todayRetailProfitLoss, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $todayWholeSale = \App\Model\SalesOrder::where('type',2)->whereDate('date', date('Y-m-d'))->sum('total');
        $todayWholeCashSale = \App\Model\SalePayment::where('sale_type_status',2)->whereDate('date', date('Y-m-d'))
            ->where('type', 1)
            ->where('received_type', 1)->sum('amount');
        $todayWholeDue = \App\Model\SalesOrder::where('type',2)->whereDate('date', date('Y-m-d'))->sum('due');
        $todayWholeInvoiceTotal = \App\Model\SalesOrder::where('type',2)->whereDate('date', date('Y-m-d'))->sum('invoice_total');
        $totalWholeDueCollection = $todayWholeCashSale-$todayWholeInvoiceTotal>0 ? $todayWholeCashSale-$todayWholeInvoiceTotal : 0;
        $todayWholeDueCollection = \App\Model\SalePayment::where('sale_type_status',2)->where('type',1)->whereDate('date', date('Y-m-d'))
            ->where('type', 1)
            ->where('received_type', 2)
            ->whereNotIn('transaction_method', [4,5])
            ->sum('amount');

        $whole_incomes = \App\Model\TransactionLog::where('sale_type_status',2)->where('transaction_type', 1)->whereIn('net_profit', [1, 2])->whereDate('date', date('Y-m-d'))->get()->sum('amount');
        $whole_expenses = \App\Model\TransactionLog::where('sale_type_status',2)->whereIn('transaction_type', [4, 2])->whereIn('balance_transfer_id', [null])->whereDate('date', date('Y-m-d'))->get()->sum('amount');
        $todayWholeProfitLoss = $whole_incomes-$whole_expenses;
        ?>
        <h5 class="mb-2 text-center"><b style="color: #22eabf;">পাইকারি বিক্রয় তথ্য</b></h5>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box wholesale-info-box shadow-none">
                    <span class="info-box-icon bg-info"><i class="far fa-heart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের বিক্রয়</span>
                        <span class="info-box-number">Tk {{ number_format($todayWholeSale , 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box wholesale-info-box shadow-sm">
                    <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের ক্যাশ বিক্রয়</span>
                        <span class="info-box-number">Tk {{ number_format($todayWholeCashSale-$totalWholeDueCollection, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box wholesale-info-box shadow">
                    <span class="info-box-icon bg-dark"><i class="far fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের বকেয়া</span>
                        <span class="info-box-number">Tk {{ number_format($todayWholeDue-$totalWholeDueCollection, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box wholesale-info-box shadow-lg">
                    <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">আজকের লাভ & লস</span>
                        <span class="info-box-number">Tk {{ number_format($todayWholeProfitLoss, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
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
