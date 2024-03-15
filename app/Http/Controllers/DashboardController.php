<?php

namespace App\Http\Controllers;

use App\Model\BranchCash;
use App\Model\Cash;
use App\Model\CompanyBranch;
use App\Model\PurchaseInventory;
use App\Model\PurchaseOrder;
use App\Model\PurchaseOrderProduct;
use App\Model\SalePayment;
use App\Model\SalesOrder;
use App\Model\SalesOrderProduct;
use App\Model\TransactionLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function home(){
        return view('home');
    }
    public function index() {
        if(\request()->get('branch')>=0 && Auth::user()->role==0){
             $user = User::where('id',Auth::user()->id)->first();
            $user->company_branch_id = \request()->get('branch');
            $user->save();
            Auth::user()->update(['company_branch_id' => \request()->get('branch')]);
        }
//        if (Auth::user()->company_branch_id == 0) {
            $todaySale = SalesOrder::whereDate('date', date('Y-m-d'))->sum('total');
            $todayInvoiceTotal = SalesOrder::whereDate('date', date('Y-m-d'))->sum('invoice_total');
            $todayDue = SalesOrder::whereDate('date', date('Y-m-d'))->sum('due');
            $todaySaleAdjustment = SalesOrder::whereDate('date', date('Y-m-d'))->sum('sale_adjustment');
            $todayDueCollection = SalePayment::whereDate('date', date('Y-m-d'))
                ->where('type', 1)
                ->where('received_type', 2)
                ->whereNotIn('transaction_method', [4,5])
                ->where('status',2)
                ->sum('amount');

            //Cash Sale
            $todayCashSale = SalePayment::whereDate('date', date('Y-m-d'))
                ->where('type', 1)
                ->where('received_type', 1)->sum('amount');

            $todayExpense = TransactionLog::whereDate('date', date('Y-m-d'))
                ->whereIn('transaction_type', [3, 2, 6])
                ->whereNotIn('transaction_method', [4, 5])
                ->whereIn('balance_transfer_id', [null])
                ->sum('amount');

            $totalStock = 0;
            $totalStockValue = 0;
            $inventories = PurchaseInventory::all();
            foreach ($inventories as $inventory){
                $totalStockValue += ($inventory->quantity*$inventory->unit_price);
                $totalStock += $inventory->quantity;
            }
            $cashInHand = Cash::first()->amount;
            $orderIds = SalesOrder::whereDate('date',date('Y-m-d'))->pluck('id')->toArray();
            $todayPairSale = SalesOrderProduct::whereIn('sales_order_id',$orderIds)->get()->sum('quantity');
            $incomes = TransactionLog::where('transaction_type', 1)->whereIn('net_profit', [1, 2])->whereDate('date', date('Y-m-d'))->get()->sum('amount');
            $expenses = TransactionLog::whereIn('transaction_type', [4, 2])->whereIn('balance_transfer_id', [null])->whereDate('date', date('Y-m-d'))->get()->sum('amount');
            $todayProfitLoss = $incomes-$expenses;
            $receivedByBank = TransactionLog::where('transaction_type', 1)->whereNotIn('net_profit', [1, 2])->whereDate('date', date('Y-m-d'))->where('bank_id','!=',null)->get()->sum('amount');
            //dd($receivedByBank);
            $companyBranches = CompanyBranch::all();
            $data = [
                'todaySale' => $todaySale,
                'todayDue' => $todayDue,
                'todaySaleAdjustment' => $todaySaleAdjustment,
                'todayDueCollection' => $todayDueCollection,
                'todayExpense' => $todayExpense,
                'todayCashSale' => $todayCashSale,
                'companyBranches'=>$companyBranches,
                'todayInvoiceTotal'=>$todayInvoiceTotal,
                'totalStock' => $totalStock,
                'totalStockValue' => $totalStockValue,
                'cashInHand' => $cashInHand,
                'todayPairSale' => $todayPairSale,
                'todayProfitLoss' => $todayProfitLoss,
                'receivedByBank' => $receivedByBank,
            ];
//        }else{

//            $todaySale = SalesOrder::where('company_branch_id', Auth::user()->company_branch_id)->whereDate('date', date('Y-m-d'))->sum('total');
//            $todayDue = SalesOrder::where('company_branch_id', Auth::user()->company_branch_id)->whereDate('date', date('Y-m-d'))->sum('due');
//            $todaySaleAdjustment = SalesOrder::where('company_branch_id', Auth::user()->company_branch_id)->whereDate('date', date('Y-m-d'))->sum('sale_adjustment');
//            //dd($todayDue);
//            $todayDueCollection = SalePayment::whereDate('date', date('Y-m-d'))
//                ->where('company_branch_id', Auth::user()->company_branch_id)
//                ->where('type', 1)
//                ->where('received_type', 2)
//                ->whereNotIn('transaction_method', [2])
//                ->sum('amount');
//
//            $todayCashSale = SalePayment::where('company_branch_id', Auth::user()->company_branch_id)->whereDate('date', date('Y-m-d'))
//                ->where('type', 1)
//                ->where('received_type', 1)->sum('amount');
//            $todayExpense = TransactionLog::where('company_branch_id', Auth::user()->company_branch_id)->whereDate('date', date('Y-m-d'))
//                ->whereIn('transaction_type', [3, 2, 6])
//                ->whereNotIn('transaction_method', [4,5])
//                ->sum('amount');
//            $cashInHand = BranchCash::where('company_branch_id',auth()->user()->company_branch_id)->first()->amount;
//            $companyBranches = CompanyBranch::all();
//            $data = [
//                'todaySale' => $todaySale,
//                'todayDue' => $todayDue,
//                'todaySaleAdjustment' => $todaySaleAdjustment,
//                'todayDueCollection' => $todayDueCollection,
//                'todayExpense' => $todayExpense,
//                'todayCashSale' => $todayCashSale,
//                'companyBranches' => $companyBranches,
//                'cashInHand' => $cashInHand,
//            ];
//        }
        return view('dashboard', $data);
    }

    public function subboard(){
        return view('subboard');
    }
}
