<?php

namespace App\Http\Controllers;

use App\Model\AccountHeadSubType;
use App\Model\AccountHeadType;
use App\Model\BalanceTransfer;
use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Branch;
use App\Model\BranchCash;
use App\Model\Cash;
use App\Model\Client;
use App\Model\ClientManagement;
use App\Model\CompanyBranch;
use App\Model\Customer;
use App\Model\Employee;
use App\Model\MobileBanking;
use App\Model\PartyLess;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\ProductItem;
use App\Model\ProductReturnOrder;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseOrder;
use App\Model\PurchasePayment;
use App\Model\SalePayment;
use App\Model\SalesOrder;
use App\Model\SubCustomer;
use App\Model\Supplier;
use App\Model\Transaction;
use App\Model\TransactionLog;
use App\Model\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function purchase(Request $request) {
        $suppliers = Supplier::orderBy('name')->get();
        $productitems = ProductItem::all();
        $inventories = PurchaseInventory::orderBy('serial')->get();
        $appends = [];
        $query = PurchaseOrder::query();

        if ($request->start && $request->end) {

            $query->whereBetween('date', [$request->start, $request->end]);
            $appends['start'] = $request->start;
            $appends['end'] = $request->end;

        }else{
            $query->whereBetween('date', [date('Y-m-d'), date('Y-m-d')]);
            $appends['start'] = date('Y-m-d');
            $appends['end'] = date('Y-m-d');
        }

        if ($request->supplier && $request->supplier != '') {
            $query->where('supplier_id', $request->supplier);
            $appends['supplier'] = $request->supplier;
        }

        if ($request->purchaseId && $request->purchaseId != '') {
            $query->where('order_no', $request->purchaseId);
            $appends['purchaseId'] = $request->purchaseId;
        }

        if ($request->product_item && $request->product_item != '') {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('purchase_order_products.product_item_id', '=', $request->product_item);
            });

            $appends['product_item'] = $request->product_item;
        }

        if ($request->product && $request->product != '') {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('purchase_order_products.product_id', '=', $request->product);
            });

            $appends['product'] = $request->product;
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        $query->with('products');
        $orderTotalAmount = 0;
        $orderPaidAmount = 0;
        $orderDueAmount = 0;
        $totalQuantity = 0;

        $totalOrders = $query->get();
        foreach ($totalOrders as $totalOrder){
            $orderTotalAmount += $totalOrder->total;
            $orderPaidAmount += $totalOrder->paid;
            $orderDueAmount += $totalOrder->due;
            $totalQuantity+=$totalOrder->quantity();
        }
        $orders = $query->paginate(10);


        foreach ($orders as $order) {
            $orderProducts = [];

            foreach ($order->products as $orderProduct)

                $orderProducts[] = $orderProduct->productItem->name;

           $order->product_name = implode(', ', $orderProducts);
        }

        return view('report.purchase', compact('orders', 'suppliers',
            'appends','productitems','orderTotalAmount','orderPaidAmount','orderDueAmount','totalQuantity'));
    }

    public function billWiseProfitLoss(Request $request){
        if (Auth::user()->company_branch_id == 0) {
            $customers = Customer::where('status', 1)->with('branch')->orderBy('name')->get();
        }else{
            $customers = Customer::where('status', 1)->where('company_branch_id',Auth::user()->company_branch_id)->orderBy('name')->get();
        }

        $saleOrders = SalesOrder::get();
        $appends = [];
        $query = SalesOrder::query();

        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
            $appends['start'] = $request->start;
            $appends['end'] = $request->end;
        }

        if ($request->customer && $request->customer != '') {
            $query->where('customer_id', $request->customer);
            $appends['customer'] = $request->customer;
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        $orders = $query->paginate(10);

        $purchase_prices = [];
        $selling_prices = [];
        $profits = [];

        foreach ($orders as $order) {
            $orderProducts = [];

            foreach ($order->products as $orderProduct)

                $orderProducts[] = $orderProduct->productItem->name??'' .' - '.$orderProduct->pivot->name??'';
            array_push($purchase_prices,$orderProduct->purchaseInventory->unit_price);
            if (auth()->user()->role == 1){
                array_push($selling_prices,$orderProduct->purchaseInventory->selling_price);
                array_push($profits,($orderProduct->purchaseInventory->selling_price) - ($orderProduct->purchaseInventory->unit_price));
            }
            else{

                array_push($selling_prices,$orderProduct->purchaseInventory->unit_price + nbrSellCalculation($orderProduct->purchaseInventory->unit_price));
                array_push($profits,($orderProduct->purchaseInventory->unit_price + nbrSellCalculation($orderProduct->purchaseInventory->unit_price)) - ($orderProduct->purchaseInventory->unit_price));
            }

            $order->product_name = implode(', ', $orderProducts);
        }


        return view('report.bill_wise_profit_loss', compact('orders', 'saleOrders',
            'appends','purchase_prices','selling_prices','profits','customers'));
    }

    public function sale(Request $request) {

        $branches =[];

        if (Auth::user()->company_branch_id == 0) {
            $customers = Customer::orderBy('name')->where('status', 1)->get();
            $suppliers = Supplier::orderBy('name')->get();
            $products = Product::orderBy('name')->get();
            $product_items = ProductItem::all();
            $appends = [];
            if ($request->company_branch != null) {
                if ($request->report_type == 2) {
                    $query = SalesOrder::whereNotIn('paid',[0])->where('company_branch_id',$request->company_branch);
                }elseif ($request->report_type == 1) {
                    $query = SalesOrder::whereNotIn('due',[0])->where('company_branch_id',$request->company_branch);
                }else {
                    $query = SalesOrder::query();
                }
            }else{
                if ($request->report_type == 2) {
                    $query = SalesOrder::whereNotIn('paid',[0]);
                }elseif ($request->report_type == 1) {
                    $query = SalesOrder::whereNotIn('due',[0]);
                }else {
                    $query = SalesOrder::query();
                }
            }
            $branches = CompanyBranch::where('status',1)->get();

        }else{
            $customers = Customer::orderBy('name')->where('company_branch_id',Auth::user()->company_branch_id)->where('status', 1)->get();
            $suppliers = Supplier::orderBy('name')->get();
            $products = Product::orderBy('name')->get();
            $product_items = ProductItem::all();
            $appends = [];
            if ($request->report_type == 2) {
                $query = SalesOrder::whereNotIn('paid',[0])->where('company_branch_id',Auth::user()->company_branch_id);
            }elseif ($request->report_type == 1) {
                $query = SalesOrder::whereNotIn('due',[0])->where('company_branch_id',Auth::user()->company_branch_id);
            }else {
                $query = SalesOrder::where('company_branch_id',Auth::user()->company_branch_id);
            }
        }


        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
            $appends['start'] = $request->start;
            $appends['end'] = $request->end;
        }else{
            $query->whereBetween('date', [date('Y-m-d'), date('Y-m-d')]);
            $appends['start'] = date('Y-m-d');
            $appends['end'] = date('Y-m-d');
        }

        if ($request->customer && $request->customer != '') {
            $query->where('customer_id', $request->customer);
            $appends['customer'] = $request->customer;
        }

        if ($request->order_no && $request->order_no != '') {
            $query->where('order_no', $request->order_no);
            $appends['order_no'] = $request->order_no;
        }

        if ($request->product_item && $request->product_item != '') {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('product_item_id', '=', $request->product_item);
            });

            $appends['product_item'] = $request->product_item;
        }

        if ($request->product && $request->product != '') {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('product_sales_order.product_id', '=', $request->product);
            });

            $appends['product_item'] = $request->product_item;
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        $query->with('products');

        $totalSaleAdjustment = 0;
        $orderTotalAmount = 0;
        $orderPaidAmount = 0;
        $orderDueAmount = 0;
        $totalQuantity = 0;
        $totalPreviousDue = 0;
        $totalReturnAmount = 0;
        $totalDiscount = 0;
        $totalTransportCost = 0;


        $orders = $query->get();
        foreach ($orders as $totalOrder){
            $orderTotalAmount += $totalOrder->sub_total;
            $orderPaidAmount += $totalOrder->paid;
            $orderDueAmount += $totalOrder->current_due;
            $totalSaleAdjustment += $totalOrder->sale_adjustment;
            $totalPreviousDue += $totalOrder->previous_due;
            $totalReturnAmount += $totalOrder->return_amount;
            $totalDiscount += $totalOrder->discount;
            $totalTransportCost += $totalOrder->transport_cost;
            $totalQuantity+=$totalOrder->quantity();
        }



        foreach ($orders as $order) {
            $orderProducts = [];
            foreach ($order->products as $orderProduct)
                $orderProducts[] = $orderProduct->productItem->name??''.' - '.$orderProduct->pivot->product_name??'';
            $order->product_name = implode(', ', $orderProducts);
        }

        return view('report.sale', compact('customers', 'products','totalDiscount','totalTransportCost',
            'appends', 'orders', 'suppliers','product_items','branches','orderTotalAmount','totalReturnAmount',
            'orderPaidAmount','orderDueAmount','totalQuantity','totalSaleAdjustment','totalPreviousDue'));
    }

    public function salePrint(Request $request) {

        //dd($request->all());

        if (Auth::user()->company_branch_id == 0) {
            $customers = Customer::orderBy('name')->where('status', 1)->get();
            $suppliers = Supplier::orderBy('name')->get();
            $products = Product::orderBy('name')->get();
            $product_items = ProductItem::all();
            $appends = [];

            if ($request->company_branch) {
                if ($request->report_type == 2) {
                    $query = SalesOrder::whereNotIn('paid',[0])->where('company_branch_id',$request->company_branch);
                }elseif ($request->report_type == 1) {
                    $query = SalesOrder::whereNotIn('due',[0])->where('company_branch_id',$request->company_branch);
                }else {
                    $query = SalesOrder::query();
                }
            }else{
                if ($request->report_type == 2) {
                    $query = SalesOrder::whereNotIn('paid',[0]);
                }elseif ($request->report_type == 1) {
                    $query = SalesOrder::whereNotIn('due',[0]);
                }else {
                    $query = SalesOrder::query();
                }
            }
            $branches = CompanyBranch::where('status',1)->get();

        }else{
            $customers = Customer::orderBy('name')->where('status', 1)->where('company_branch_id',Auth::user()->company_branch_id)->where('status', 1)->get();
            $suppliers = Supplier::orderBy('name')->get();
            $products = Product::orderBy('name')->get();
            $product_items = ProductItem::all();
            $appends = [];
            $query = SalesOrder::where('company_branch_id',Auth::user()->company_branch_id);
        }

        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
            $appends['date'] = $request->date;

        }

        if ($request->customer && $request->customer != '') {
            $query->where('customer_id', $request->customer);
            $appends['customer'] = $request->customer;
        }

        if ($request->order_no && $request->order_no != '') {
            $query->where('order_no', $request->order_no);
            $appends['order_no'] = $request->order_no;
        }

        if ($request->product_item && $request->product_item != '') {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('product_item_id', '=', $request->product_item);
            });

            $appends['product_item'] = $request->product_item;
        }

        if ($request->product && $request->product != '') {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('product_sales_order.product_id', '=', $request->product);
            });

            $appends['product_item'] = $request->product_item;
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        $query->with('products');
        $orders = $query->get();

        foreach ($orders as $order) {
            $orderProducts = [];

            foreach ($order->products as $orderProduct)
                $orderProducts[] = $orderProduct->productItem->name??''.' - '.$orderProduct->pivot->product_name??'';

            $order->product_name = implode(', ', $orderProducts);
        }

        return view('report.sale_print', compact('customers', 'products',
            'appends', 'orders', 'suppliers','product_items'));
    }

    public function balanceSummary() {
        $bankAccounts = BankAccount::where('status', 1)->with('bank', 'branch')->get();
        $cash = Cash::first();
        $mobile_banking = MobileBanking::first();
        $customerTotal = Customer::all()->where('status', 1)->sum('total');
        $customerTotalDue = Customer::all()->where('status', 1)->sum('due');
        $customerTotalPaid = Customer::all()->where('status', 1)->sum('paid');
        $supplierSaleTotal = Supplier::all()->sum('total');
        $supplierSaleDue = Supplier::all()->sum('due');
        $supplierSalePaid = Supplier::all()->sum('paid');
        $totalSaleProductStock = 0;
        $inventories = PurchaseInventory::with('productItem')->get();

        /*foreach ($saleProductStocks as $stock) {
            $totalSaleProductStock += $stock->amount * $stock->product->price;
        }*/


        $suppliers = Supplier::all();
        $totalInventory = PurchaseInventory::select(DB::raw('SUM(`unit_price` * `quantity`) AS total'))->get();


        return view('report.balance_summary', compact('customerTotal','customerTotalDue','bankAccounts',
            'cash', 'mobile_banking', 'customerTotalPaid', 'suppliers', 'inventories','totalSaleProductStock',
            'supplierSaleTotal', 'supplierSaleDue', 'supplierSalePaid', 'totalInventory'));
    }

    public function profitAndLoss(Request $request) {
        $incomes = null;
        $expenses = null;
//        if (Auth::user()->company_branch_id > 0){
//            if ($request->start && $request->end) {
//                $incomes = TransactionLog::where('transaction_type', 1)->where('company_branch_id', Auth::user()->company_branch_id)->whereIn('net_profit', [1, 2])->whereBetween('date', [$request->start, $request->end])->get();
//                $expenses = TransactionLog::whereIn('transaction_type', [4, 2])->where('company_branch_id', Auth::user()->company_branch_id)->whereNull('balance_transfer_id')->whereBetween('date', [$request->start, $request->end])->get();
//            }
//        }else{
//            if ($request->start && $request->end) {
//                $incomes = TransactionLog::where('transaction_type', 1)->whereIn('net_profit', [1, 2])->whereBetween('date', [$request->start, $request->end])->get();
//                $expenses = TransactionLog::whereIn('transaction_type', [4, 2])->whereNull('balance_transfer_id')->whereBetween('date', [$request->start, $request->end])->get();
//            }
//        }
        if ($request->start && $request->end && $request->sale_type == 1) {
            $incomes = TransactionLog::where('transaction_type', 1)->where('sale_type_status',1)->whereIn('net_profit', [1, 2])->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [4, 2])->where('sale_type_status',1)->whereNull('balance_transfer_id')->whereBetween('date', [$request->start, $request->end])->get();
        }
        if ($request->start && $request->end && $request->sale_type == 2) {
            $incomes = TransactionLog::where('transaction_type', 1)->where('sale_type_status',2)->whereIn('net_profit', [1, 2])->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [4, 2])->where('sale_type_status',2)->whereNull('balance_transfer_id')->whereBetween('date', [$request->start, $request->end])->get();
        }
//return($expenses);
        return view('report.profit_and_loss', compact('incomes', 'expenses'));
    }

    public function ledger(Request $request) {
        $incomes = null;
        $expenses = null;
        $accountHead = AccountHeadType::all();

        if($request->account_head_type == '' && $request->account_head_sub_type == '' && $request->start !='' && $request->end !='') {
            $incomes = TransactionLog::where('transaction_type', 1)
                ->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [3, 2])
                ->whereBetween('date', [$request->start, $request->end])->get();

        }elseif ($request->account_head_type !='' && $request->account_head_sub_type !='' && $request->start !='' && $request->end !=''  ) {

            $incomes = TransactionLog::where('transaction_type', 1)
                ->where('account_head_type_id', $request->account_head_type)
                ->where('account_head_sub_type_id', $request->account_head_sub_type)
                ->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [3, 2])
                ->where('account_head_type_id', $request->account_head_type)
                ->where('account_head_sub_type_id', $request->account_head_sub_type)
                ->whereBetween('date', [$request->start, $request->end])->get();
        }

        return view('report.ledger', compact('incomes', 'expenses','accountHead'));
    }

    public function cashbook(Request $request) {

        $result = null;
        $openingBalance = null;

        if ($request->start && $request->end) {

            $result = collect();
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);

            $daysCount = $start->diffInDays($end);

            $cash = Cash::first();

            $initialBalance = $cash->opening_balance;
            if (Auth::user()->company_branch_id > 0){
                $initialBalance = 0;
            }

            $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($request->start)));

            if (Auth::user()->company_branch_id == 0) {
                $openingCash = Cash::first();
                $openingCashTotal = $openingCash->opening_balance;
                $totalIncome = TransactionLog::whereIn('transaction_type', [1])
                    ->where('transaction_method', 1)
                    ->whereDate('date', '<=', $previousDay)
                    ->where('net_profit','!=', 1)
                    ->sum('amount');

                $totalExpense = TransactionLog::where('transaction_type', 2)
                    ->where('transaction_method', 1)
                    ->whereDate('date', '<=', $previousDay)
                    ->sum('amount');
            }else{
                $openingBranchCash = BranchCash::where('company_branch_id',Auth::user()->company_branch_id)->first();
                $openingCashTotal = $openingBranchCash->opening_balance;
                $totalIncome = TransactionLog::where('company_branch_id',Auth::user()->company_branch_id)->whereIn('transaction_type',[1,3])
                    ->whereIn('transaction_method', [1])
                    ->whereDate('date', '<=', $previousDay)
                    ->where('net_profit','!=', 1)
                    ->sum('amount');

                $totalExpense = TransactionLog::where('company_branch_id',Auth::user()->company_branch_id)->where('transaction_type', 2)
                    ->whereIn('transaction_method', [1])
                    ->whereDate('date', '<=', $previousDay)
                    ->sum('amount');
            }

            $openingBalance = ($initialBalance + $totalIncome + $openingCashTotal) - $totalExpense;

            //dd($openingBalance);

            //$result->push(['date' => $request->start_date, 'particular' => 'Opening Balance', 'debit' => '', 'credit' => '', 'balance' => $openingBalance]);

            for($i=0; $i<=$daysCount; $i++) {
                $date = date('Y-m-d', strtotime($start. ' + '.$i.' days'));

                if (Auth::user()->company_branch_id == 0) {
                    $incomes = TransactionLog::whereIn('transaction_type',[1,3])->where('transaction_method', 1)->where('net_profit','!=', 1)->where('date', $date)->get();
                    $expenses = TransactionLog::where('transaction_type', 2)->whereIn('transaction_method', [1,2])->where('date', $date)->get();
                }else{

                    $incomes = TransactionLog::whereIn('transaction_type', [1,3])->where('transaction_method', 1)->where('net_profit','!=', 1)->where('date', $date)->where('company_branch_id' , Auth::user()->company_branch_id)->get();
                    $expenses = TransactionLog::where('transaction_type', 2)->whereIn('transaction_method', [1,2])->where('date', $date)->where('company_branch_id' , Auth::user()->company_branch_id)->get();
//                    dd($expenses);
                }

                $result->push(['date' => $date, 'incomes' => $incomes, 'expenses' => $expenses]);
            }
        }
//        return($result);
        return view('report.cashbook', compact('result','openingBalance'));
    }
    public function dailyReport(Request $request) {

        $result = null;
        $openingBalance = null;

        if ($request->start && $request->end) {

            $result = collect();
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);

            $daysCount = $start->diffInDays($end);

            $cash = Cash::first();

            $initialBalance = $cash->opening_balance;
            if (Auth::user()->company_branch_id > 0){
                $initialBalance = 0;
            }

            $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($request->start)));

            if (Auth::user()->company_branch_id == 0) {
                $openingCash = Cash::first();
                $openingCashTotal = $openingCash->opening_balance;
                $totalIncome = TransactionLog::whereIn('transaction_type', [1])
                    ->where('transaction_method', 1)
                    ->whereDate('date', '<=', $previousDay)
                    ->where('net_profit','!=', 1)
                    ->sum('amount');

                $totalExpense = TransactionLog::where('transaction_type', 2)
                    ->where('transaction_method', 1)
                    ->whereDate('date', '<=', $previousDay)
                    ->sum('amount');
            }else{
                $openingBranchCash = BranchCash::where('company_branch_id',Auth::user()->company_branch_id)->first();
                $openingCashTotal = $openingBranchCash->opening_balance;
                $totalIncome = TransactionLog::where('company_branch_id',Auth::user()->company_branch_id)->whereIn('transaction_type',[1,3])
                    ->whereIn('transaction_method', [1])
                    ->whereDate('date', '<=', $previousDay)
                    ->where('net_profit','!=', 1)
                    ->sum('amount');

                $totalExpense = TransactionLog::where('company_branch_id',Auth::user()->company_branch_id)->where('transaction_type', 2)
                    ->whereIn('transaction_method', [1])
                    ->whereDate('date', '<=', $previousDay)
                    ->sum('amount');
            }

            $openingBalance = ($initialBalance + $totalIncome + $openingCashTotal) - $totalExpense;

            //dd($openingBalance);

            //$result->push(['date' => $request->start_date, 'particular' => 'Opening Balance', 'debit' => '', 'credit' => '', 'balance' => $openingBalance]);

            for($i=0; $i<=$daysCount; $i++) {
                $date = date('Y-m-d', strtotime($start. ' + '.$i.' days'));

                if (Auth::user()->company_branch_id == 0) {
                    $incomes = TransactionLog::whereIn('transaction_type',[1,3])->where('transaction_method', 1)->where('net_profit','!=', 1)->where('date', $date)->get();
                    $expenses = TransactionLog::where('transaction_type', 2)->whereIn('transaction_method', [1,2])->where('date', $date)->get();
                }else{

                    $incomes = TransactionLog::whereIn('transaction_type', [1,3])->where('transaction_method', 1)->where('net_profit','!=', 1)->where('date', $date)->where('company_branch_id' , Auth::user()->company_branch_id)->get();
                    $expenses = TransactionLog::where('transaction_type', 2)->whereIn('transaction_method', [1,2])->where('date', $date)->where('company_branch_id' , Auth::user()->company_branch_id)->get();
//                    dd($expenses);
                }

                $result->push(['date' => $date, 'incomes' => $incomes, 'expenses' => $expenses]);
            }
        }
        $total_retail_incomes = null;
        $total_retail_expenses = null;
        if ($request->start && $request->end) {
            $total_retail_incomes = TransactionLog::where('transaction_type', 1)->whereIn('net_profit', [1, 2])->whereBetween('date', [$request->start, $request->end])->get();
            $total_retail_expenses = TransactionLog::whereIn('transaction_type', [4, 2])->whereNull('balance_transfer_id')->whereBetween('date', [$request->start, $request->end])->get();
        }
        $total_whole_incomes = null;
        $total_whole_expenses = null;
        if ($request->start && $request->end) {
            $total_whole_incomes = TransactionLog::where('transaction_type', 1)->whereIn('net_profit', [1, 2])->whereBetween('date', [$request->start, $request->end])->get();
            $total_whole_expenses = TransactionLog::whereIn('transaction_type', [4, 2])->whereNull('balance_transfer_id')->whereBetween('date', [$request->start, $request->end])->get();
        }
//        return($result);
        return view('report.daily_report', compact('result','openingBalance','total_retail_incomes','total_retail_expenses','total_whole_incomes','total_whole_expenses'));
    }

    public function monthlyExpenditure(Request $request) {
        $selectedMonthInText = '';
        $result = null;

        if ($request->year && $request->month) {
            $result = collect();
            $selectedMonthInText = date('F, Y', mktime(0,0,0,$request->month, 1, $request->year));
            $dateObj =  Carbon::parse($request->year.'-'.$request->month);
            $startDate = $dateObj->startOfMonth()->format('Y-m-d');
            $endDate = $dateObj->endOfMonth()->format('Y-m-d');

            // Supplier Payment
            $supplierPaymentTotal = PurchasePayment::whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $result->push([
                'particular' => 'Supplier Payment',
                'total' => $supplierPaymentTotal
            ]);

            // Transaction
            $transactionsIds = Transaction::select('account_head_type_id')
                ->where('transaction_type', 2)
                ->groupBy('account_head_type_id')
                ->whereBetween('date', [$startDate, $endDate])
                ->get()->pluck('account_head_type_id')->toArray();

            foreach ($transactionsIds as $trxId) {
                $total = Transaction::where('transaction_type', 2)
                    ->where('account_head_type_id', $trxId)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount');

                $accountHeadType = AccountHeadType::find($trxId);

                $result->push([
                    'particular' => $accountHeadType->name,
                    'total' => $total
                ]);
            }
        }
        return view('report.monthly_expenditure', compact('result', 'selectedMonthInText'));
    }
    public function cashStatement(Request $request) {
        $result = null;
        $metaData = null;
        if ($request->start && $request->end) {
            $cashAccount = Cash::first();

            $metaData = [
                'start_date' => $request->start,
                'end_date' => $request->end,
            ];

            $result = collect();

            $initialBalance = $cashAccount->opening_balance;

            $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($request->start)));

            if (Auth::user()->company_branch_id == 0) {

                $totalIncome = TransactionLog::where('transaction_type', 1)
                    ->where('transaction_method', 1)
                    ->whereDate('date', '<=', $previousDay)
                    ->orderBy('date')
                    ->sum('amount');

                $totalExpense = TransactionLog::where('transaction_type', 2)
                    ->where('transaction_method', 1)
                    ->whereDate('date', '<=', $previousDay)
                    ->orderBy('date')
                    ->sum('amount');
            }else{
                $totalIncome = TransactionLog::where('transaction_type', 1)
                    ->where('transaction_method', 1)
                    ->where('company_branch_id', Auth::user()->company_branch_id)
                    ->whereDate('date', '<=', $previousDay)
                    ->orderBy('date')
                    ->sum('amount');

                $totalExpense = TransactionLog::where('transaction_type', 2)
                    ->where('transaction_method', 1)
                    ->where('company_branch_id', Auth::user()->company_branch_id)
                    ->whereDate('date', '<=', $previousDay)
                    ->orderBy('date')
                    ->sum('amount');
            }

            $openingBalance = $initialBalance + ($totalIncome) - $totalExpense;

            $result->push(['date' => $request->start_date, 'particular' => 'Opening Balance', 'debit' => '', 'credit' => '', 'balance' => $openingBalance]);

            if (Auth::user()->company_branch_id == 0) {
                $transactionLogs = TransactionLog::whereBetween('date', [$request->start, $request->end])
                    ->where('transaction_method', 1)
                    ->get();
            }else{
                $transactionLogs = TransactionLog::whereBetween('date', [$request->start, $request->end])
                    ->where('transaction_method', 1)
                    ->where('company_branch_id', Auth::user()->company_branch_id)
                    ->get();
            }

            $balance = $openingBalance;
            $totalDebit = 0;
            $totalCredit = 0;
            foreach ($transactionLogs as $log) {
                if ($log->transaction_type == 1) {
                    // Income
                    $balance += $log->amount ;
                    $totalDebit += $log->amount ;
                    $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => $log->amount, 'credit' => '', 'balance' => $balance]);
                } else {
                    $balance -= $log->amount;
                    $totalCredit += $log->amount;
                    $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => '', 'credit' => $log->amount, 'balance' => $balance]);
                }
            }

            $metaData['total_debit'] = $totalDebit;
            $metaData['total_credit'] = $totalCredit;

        }

        return view('report.cash_statement', compact( 'result', 'metaData'));
    }

    public function bankStatement(Request $request) {
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $result = null;
        $metaData = null;
        if ($request->bank && $request->branch && $request->account && $request->start && $request->end) {
            $bankAccount = BankAccount::where('id', $request->account)->first();
            $bank = Bank::where('id', $request->bank)->first();
            $branch = Branch::where('id', $request->branch)->first();

            $metaData = [
                'name' => $bank->name,
                'branch' => $branch->name,
                'account' => $bankAccount->account_no,
                'start_date' => $request->start,
                'end_date' => $request->end,
            ];

            $result = collect();

            $initialBalance = $bankAccount->opening_balance;

            $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($request->start)));

            $totalIncome = TransactionLog::where('transaction_type', 1)
                ->where('bank_account_id', $request->account)
                ->whereDate('date', '<=', $previousDay)
                ->sum('amount');

            $totalExpense = TransactionLog::where('transaction_type', 2)
                ->where('bank_account_id', $request->account)
                ->whereDate('date', '<=', $previousDay)
                ->sum('amount');

            $openingBalance = $initialBalance + ($totalIncome) - $totalExpense;

            $result->push(['date' => $request->start_date, 'particular' => 'Opening Balance', 'debit' => '', 'credit' => '', 'balance' => $openingBalance]);

            $transactionLogs = TransactionLog::where('bank_account_id', $request->account)
                ->whereBetween('date', [$request->start, $request->end])
                ->get();

            $balance = $openingBalance;
            $totalDebit = 0;
            $totalCredit = 0;
            foreach ($transactionLogs as $log) {
                if ($log->transaction_type == 1) {
                    // Income
                    $balance += $log->amount ;
                    $totalDebit += $log->amount  ;
                    $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => $log->amount, 'credit' => '', 'balance' => $balance]);
                } else {
                    $balance -= $log->amount;
                    $totalCredit += $log->amount;
                    $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => '', 'credit' => $log->amount, 'balance' => $balance]);
                }
            }

            $metaData['total_debit'] = $totalDebit;
            $metaData['total_credit'] = $totalCredit;

        }

        return view('report.bank_statement', compact('banks', 'result', 'metaData'));
    }

    public function clientStatement(Request $request) {

        if (Auth::user()->company_branch_id == 0) {
            if ($request->customer == null) {
                $report_type = $request->report_type??1;
                $customers = Customer::where('type',$request->sale_type)->orderBy('id','asc')
                    ->where('status',1)
                    ->get();
            }else{
                $report_type = $request->report_type??1;
                $customers = Customer::where('type',$request->sale_type)->where('id',$request->customer)
                    ->where('status',1)
                    ->get();
            }
        }else{
            if ($request->customer == null) {
                $report_type = $request->report_type??1;
                $customers = Customer::where('type',$request->sale_type)->orderBy('id','asc')
                    ->where('status',1)
                    ->where('company_branch_id',Auth::user()->company_branch_id)
                    ->get();
            }else{
                $report_type = $request->report_type??1;
                $customers = Customer::where('type',$request->sale_type)->where('id',$request->customer)
                    ->where('status',1)
                    ->where('company_branch_id',Auth::user()->company_branch_id)
                    ->get();
            }
        }

        return view('report.client_statement',compact('customers', 'report_type'));
    }


    public function branchWiseClient(Request $request) {

        $customers = [];
        $customer = null;
        if (Auth::user()->company_branch_id == 0) {
            $companyBranches = CompanyBranch::all();
        }else{
            $companyBranches = CompanyBranch::where('id',Auth::user()->company_branch_id);
        }

        if ($request->branch && $request->customer) {
            $customer = Customer::where('company_branch_id',$request->branch)
                ->where('id',$request->customer)
                ->first();
        }elseif ($request->branch) {
            $customers = Customer::where('company_branch_id',$request->branch)->get();
        }

        return view('report.branch_wise_client',compact('customers', 'companyBranches','customer'));
    }

    public function partyLedger(Request $request){
        $party_discount_amount = 0;
        if (Auth::user()->company_branch_id == 0) {
            $clients = Customer::where('status',1)->orderBy('name')
                ->get();
            $clientName = '';
            $clientHistories = [];
        }else{
            $clients = Customer::where('status',1)
                ->where('company_branch_id',Auth::user()->company_branch_id)
                ->get();
            $clientName = '';
            $clientHistories = [];
        }
        if ($request->client && $request->client != ''
            && $request->start && $request->start != ''
            && $request->end && $request->end != '') {

            $startDateArray = [];
            $endDateArray = [];

            $clientName = $openingDue = Customer::where('id',$request->client)
                ->first();

            $firstOrder = SalesOrder::where('customer_id',$request->client)
                ->orderBy('date','asc')
                ->first();

            $lastOrder = SalesOrder::where('customer_id',$request->client)
                ->orderBy('date','desc')
                ->first();

            $firstPayment = SalePayment::where('customer_id',$request->client)
                ->orderBy('date','asc')
                ->first();

            $lastPayment = SalePayment::where('customer_id',$request->client)
                ->orderBy('date','desc')
                ->first();

            $firstReturn = SalesOrder::where('customer_id',$request->client)
                ->orderBy('date','asc')
                ->first();

            $lastReturn = SalesOrder::where('customer_id',$request->client)
                ->orderBy('date','desc')
                ->first();

            if ($firstOrder !=''){
                array_push($startDateArray,$openingDue->created_at->format('d-m-Y'));

                array_push($endDateArray,$openingDue->created_at->format('d-m-Y'));
            }

            if ($firstOrder)
                array_push($startDateArray,$firstOrder->date->format('d-m-Y'));

            if ($firstPayment)
                array_push($startDateArray,$firstPayment->date->format('d-m-Y'));

            if ($firstReturn)
                array_push($startDateArray,$firstReturn->date->format('d-m-Y'));

            if ($lastOrder)
                array_push($endDateArray,$lastOrder->date->format('d-m-Y'));
            if ($lastPayment)
                array_push($endDateArray,$lastPayment->date->format('d-m-Y'));
            if ($lastReturn)
                array_push($endDateArray,$lastReturn->date->format('d-m-Y'));


            if (count($startDateArray) > 0 && count($endDateArray) > 0){


                array_multisort(array_map('strtotime', $startDateArray), $startDateArray);
                array_multisort(array_map('strtotime', $endDateArray), $endDateArray);

                $startMin = $startDateArray[0];
                $endMax = $endDateArray[array_key_last($endDateArray)];


                $startDateObj = new Carbon($startMin);
                $endDateObj = new Carbon($endMax);


                $totalDurationLengths = $startDateObj->diffInDays($endDateObj) + 1;
                //dd($totalDurationLengths);

                $clientHistories = [];
                array_push($clientHistories,[
                    'date'=>$openingDue->created_at->format('d-m-Y') ?? '',
                    'particular'=>'Opening Balance',
                    'quantity'=>0,
                    'invoice'=>0,
                    'discount'=>0,
                    'transport_cost'=>0,
                    'return'=> 0,
                    'sale_adjustment'=> 0,
                    'party_discount' => 0,
                    'payment'=>0,
                    'due_balance'=>$openingDue->opening_due,
                ]);

                $orderPayment = [];

                for ($i = 0; $i < $totalDurationLengths;$i++) {
                    $date = Carbon::createFromFormat('d-m-Y',$startMin);
                    $searchDate = $date->addDays($i)->format('Y-m-d');

                    $orders = SalesOrder::where('customer_id',$request->client)
                        ->whereBetween('date', [$request->start, $request->end])
                        ->where('date',$searchDate)->get();

                    foreach ($orders as $order){
                        if ($order->total <= 0 || $order->return_amount > 0 || $order->sale_adjustment > 0 || $order->discount > 0 ) {
                            array_push($clientHistories,[
                                'date'=>$order->date->format('d-m-Y') ?? '',
                                'particular' => 'Invoice Total'.' '.$order->customer->name.' '.$order->order_no??'',
                                'quantity'=>$order->quantity() ??0,
                                'invoice'=>$order->sub_total,
                                'discount'=>$order->discount ?? '0',
                                'transport_cost'=>$order->transport_cost ?? '0',
                                'return'=> $order->return_amount??'0',
                                'sale_adjustment'=> $order->sale_adjustment??'0',
                                'party_discount' => 0,
                                'payment'=> 0,
                                'due_balance'=>$order->sub_total - $order->discount-$order->sale_adjustment+$order->transport_cost,
                            ]);


                            $salePayments = SalePayment::where('sales_order_id',$order->id)->where('status',2)->get();
                            foreach ($salePayments as $salePayment){
//                                if ($salePayment->transactionLog->payment_cheak_status != 3){
                                array_push($clientHistories,[
                                    'date'=>$order->date->format('d-m-Y') ?? '',
                                    'particular' => 'Payment From'.' '.$order->customer->name.' '.$order->order_no??'',
                                    'quantity'=>0,
                                    'invoice'=>0,
                                    'discount'=>0,
                                    'transport_cost'=>0,
                                    'return'=> 0,
                                    'sale_adjustment'=> 0,
                                    'party_discount' => 0,
                                    'payment'=> $salePayment->amount,
                                    'due_balance'=>0,
                                ]);
//                                }elseif ($salePayment->transactionLog->payment_cheak_status == 3){
//                                    array_push($clientHistories,[
//                                        'date'=>$order->date->format('d-m-Y') ?? '',
//                                        'particular' => $salePayment->transactionLog->particular.' '.$order->order_no??'',
//                                        'quantity'=>0,
//                                        'invoice'=>0,
//                                        'return'=>0,
//                                        'payment'=> $salePayment->amount,
//                                        'due_balance'=>0,
//                                    ]);
//                                }
                            }

//                            foreach ($salePayments as $payment){
//                                if ($payment->transaction_method == 4) {
//                                    array_push($clientHistories,[
//                                        'date'=>$payment->date->format('d-m-Y') ?? '',
//                                        'particular'=>$payment->customer->name.'-'.'Balance Adjustment-'.$payment->id,
//                                        'quantity'=>0,
//                                        'invoice'=> 0,
//                                        'return'=> 0,
//                                        'payment'=>$payment->amount,
//                                        'due_balance'=>0,
//                                    ]);
//                                }
//                            }
//
//                            foreach ($salePayments as $payment) {
//                                if($payment->transaction_method == 5) {
//                                    array_push($clientHistories, [
//                                        'date' => $payment->date->format('d-m-Y') ?? '',
//                                        'particular' => $payment->customer->name . '-' . 'Return Adjustment Amount-' . $payment->id,
//                                        'quantity' => 0,
//                                        'invoice' => 0,
//                                        'return' => $payment->amount,
//                                        'payment' => 0,
//                                        'due_balance' => 0,
//                                    ]);
//                                }
//                            }

                        }else{

                            array_push($clientHistories,[
                                'date'=>$order->date->format('d-m-Y') ?? '',
                                'particular' => 'Invoice Total'.' '.$order->customer->name.' '.$order->order_no??'',
                                'quantity'=>$order->quantity() ??0,
                                'invoice'=>$order->sub_total,
                                'discount'=>$order->discount ?? '0',
                                'transport_cost'=>$order->transport_cost ?? '0',
                                'return'=> $order->return_amount??'0',
                                'sale_adjustment'=> $order->sale_adjustment??'0',
                                'party_discount' => 0,
                                'payment'=> 0,
                                'due_balance'=>$order->sub_total - $order->discount-$order->sale_adjustment+$order->transport_cost,

                            ]);


                            $salePayments = SalePayment::with('transactionLog')->where('sales_order_id',$order->id)->where('status',2)->get();

                            //dd();

                            foreach ($salePayments as $salePayment){
//                                if ($salePayment->transactionLog->payment_cheak_status != 3){
                                array_push($clientHistories,[
                                    'date'=>$order->date->format('d-m-Y') ?? '',
                                    'particular' => 'Payment From'.' '.$order->customer->name.' '.$order->order_no??'',
                                    'quantity'=>0,
                                    'invoice'=>0,
                                    'discount'=>0,
                                    'transport_cost'=>0,
                                    'return'=> 0,
                                    'sale_adjustment'=> 0,
                                    'party_discount' => 0,
                                    'payment'=> $salePayment->amount,
                                    'due_balance'=>0,
                                ]);
//                                }elseif ($salePayment->transactionLog->payment_cheak_status == 3){
//                                    array_push($clientHistories,[
//                                        'date'=>$order->date->format('d-m-Y') ?? '',
//                                        'particular' => $salePayment->transactionLog->particular.' '.$order->order_no??'',
//                                        'quantity'=>0,
//                                        'invoice'=>0,
//                                        'return'=>0,
//                                        'payment'=> $salePayment->amount,
//                                        'due_balance'=>0,
//                                    ]);
//                                }

                            }

//                            foreach ($salePayments as $payment){
//                                if ($payment->transaction_method == 4) {
//                                    array_push($clientHistories,[
//                                        'date'=>$payment->date->format('d-m-Y') ?? '',
//                                        'particular'=>$payment->customer->name.'-'.'Balance Adjustment-'.$payment->id,
//                                        'quantity'=>0,
//                                        'invoice'=> 0,
//                                        'return'=> 0,
//                                        'payment'=>$payment->amount,
//                                        'due_balance'=>0,
//                                    ]);
//                                }
//                            }
//                            foreach ($salePayments as $payment) {
//
//                                if($payment->transaction_method == 5) {
//                                    array_push($clientHistories, [
//                                        'date' => $payment->date->format('d-m-Y') ?? '',
//                                        'particular' => $payment->customer->name . '-' . 'Return Adjustment Amount-' . $payment->id,
//                                        'quantity' => 0,
//                                        'invoice' => 0,
//                                        'return' => $payment->amount,
//                                        'payment' => 0,
//                                        'due_balance' => 0,
//                                    ]);
//                                }
//                            }

                        }
                    }


                    $payments = SalePayment::where('status',2)
                        ->where('customer_id',$request->client)
                        ->whereBetween('date', [$request->start, $request->end])
                        ->where('date',$searchDate)
                        ->where('sales_order_id','=',null)
                        ->get();

                    foreach ($payments as $payment) {
//                        if ($payment->transactionLog->payment_cheak_status != 3){
                        if ($payment->transaction_method != 4 && $payment->transaction_method != 5){
                            array_push($clientHistories, [
                                'date' => $payment->date->format('d-m-Y') ?? '',
                                // 'particular' => 'Receipt From'.' '.$payment->customer->name.' '.'Without Invoice',
                                'particular' => 'Receipt From' . ' ' . $payment->customer->name . ' ' . $payment->id?? '',
                                'quantity' => 0,
                                'invoice' => 0,
                                'discount'=>0,
                                'transport_cost'=>0,
                                'return'=> 0,
                                'sale_adjustment'=> 0,
                                'party_discount' => 0,
                                'payment' => $payment->amount,
                                'due_balance' => 0,
                            ]);
                        }
//                        }elseif ($payment->transactionLog->payment_cheak_status == 3){
//                            if ($payment->transaction_method != 4 && $payment->transaction_method != 5){
//                                array_push($clientHistories, [
//                                    'date' => $payment->date->format('d-m-Y') ?? '',
//                                    // 'particular' => 'Receipt From'.' '.$payment->customer->name.' '.'Without Invoice',
//                                    'particular' => $payment->transactionLog->particular .' ' . $order->order_no ?? '',
//                                    'quantity' => 0,
//                                    'invoice' => 0,
//                                    'return' => 0,
//                                    'payment' => $payment->amount,
//                                    'due_balance' => 0,
//                                ]);
//                            }
//                        }


                    }

                    foreach ($payments as $payment){
                        if ($payment->transaction_method == 4) {
                            array_push($clientHistories,[
                                'date'=>$payment->date->format('d-m-Y') ?? '',
                                'particular'=>$payment->customer->name.'-'.'Balance Adjustment-'.$payment->id,
                                'quantity'=>0,
                                'invoice'=> 0,
                                'discount'=>0,
                                'transport_cost'=>0,
                                'return'=> 0,
                                'sale_adjustment'=> 0,
                                'party_discount' => 0,
                                'payment'=>$payment->amount,
                                'due_balance'=>0,
                            ]);
                        }
                    }
                    foreach ($payments as $payment) {

                        if($payment->transaction_method == 5) {
                            array_push($clientHistories, [
                                'date' => $payment->date->format('d-m-Y') ?? '',
                                'particular' => $payment->customer->name . '-' . 'Return Adjustment Amount-' . $payment->id,
                                'quantity' => 0,
                                'invoice' => 0,
                                'discount'=>0,
                                'transport_cost'=>0,
                                'return' => $payment->amount,
                                'sale_adjustment'=> 0,
                                'party_discount' => 0,
                                'payment' => 0,
                                'due_balance' => 0,
                            ]);
                        }
                    }
                }
            }

        }elseif ($request->client && $request->client != ''){

            $party_discount_amount = PartyLess::where('customer_id', $request->client)->sum('amount');

            $startDateArray = [];
            $endDateArray = [];

            $clientName = $openingDue = Customer::where('id',$request->client)
                ->first();

            $firstOrder = SalesOrder::where('customer_id',$request->client)
                ->orderBy('date','asc')
                ->first();

            $lastOrder = SalesOrder::where('customer_id',$request->client)
                ->orderBy('date','desc')
                ->first();

            $firstPayment = SalePayment::where('customer_id',$request->client)
                ->orderBy('date','asc')
                ->first();

            $lastPayment = SalePayment::where('customer_id',$request->client)
                ->orderBy('date','desc')
                ->first();

            $firstReturn = SalesOrder::where('customer_id',$request->client)
                ->orderBy('date','asc')
                ->first();

            $lastReturn = SalesOrder::where('customer_id',$request->client)
                ->orderBy('date','desc')
                ->first();

            if ($firstOrder !=''){
                array_push($startDateArray,$openingDue->created_at->format('d-m-Y'));

                array_push($endDateArray,$openingDue->created_at->format('d-m-Y'));
            }

            if ($firstOrder)
                array_push($startDateArray,$firstOrder->date->format('d-m-Y'));

            if ($firstPayment)
                array_push($startDateArray,$firstPayment->date->format('d-m-Y'));

            if ($firstReturn)
                array_push($startDateArray,$firstReturn->date->format('d-m-Y'));

            if ($lastOrder)
                array_push($endDateArray,$lastOrder->date->format('d-m-Y'));
            if ($lastPayment)
                array_push($endDateArray,$lastPayment->date->format('d-m-Y'));
            if ($lastReturn)
                array_push($endDateArray,$lastReturn->date->format('d-m-Y'));


            if (count($startDateArray) > 0 && count($endDateArray) > 0){


                array_multisort(array_map('strtotime', $startDateArray), $startDateArray);
                array_multisort(array_map('strtotime', $endDateArray), $endDateArray);

                $startMin = $startDateArray[0];
                $endMax = $endDateArray[array_key_last($endDateArray)];


                $startDateObj = new Carbon($startMin);
                $endDateObj = new Carbon($endMax);


                $totalDurationLengths = $startDateObj->diffInDays($endDateObj) + 1;


                $clientHistories = [];
                array_push($clientHistories,[
                    'date'=>$openingDue->created_at->format('d-m-Y') ?? '',
                    'particular'=>'Opening Balance',
                    'quantity'=>0,
                    'invoice'=>0,
                    'discount'=>0,
                    'transport_cost'=>0,
                    'return'=> 0,
                    'sale_adjustment'=> 0,
                    'party_discount' => 0,
                    'payment'=>0,
                    'due_balance'=>$openingDue->opening_due,
                ]);

                for ($i = 0; $i < $totalDurationLengths;$i++) {
                    $date = Carbon::createFromFormat('d-m-Y',$startMin);
                    $searchDate = $date->addDays($i)->format('Y-m-d');

                    $orders = SalesOrder::where('customer_id',$request->client)
                        ->where('date',$searchDate)->get();


                    foreach ($orders as $order){
                        if ($order->total <= 0 || $order->return_amount > 0|| $order->sale_adjustment > 0 || $order->discount > 0 )  {
                            array_push($clientHistories,[
                                'date'=>$order->date->format('d-m-Y') ?? '',
                                'particular' => 'Invoice Total'.' '.$order->customer->name.' '.$order->order_no??'',
                                'quantity'=>$order->quantity() ??0,
                                'invoice'=>$order->sub_total,
                                'discount'=>$order->discount ?? '0',
                                'transport_cost'=>$order->transport_cost ?? '0',
                                'return'=> $order->return_amount??'0',
                                'sale_adjustment'=> $order->sale_adjustment??'0',
                                'party_discount' => 0,
                                'payment'=> 0,
                                'due_balance'=>$order->sub_total - $order->discount-$order->sale_adjustment+$order->transport_cost,
                            ]);

                            $salePayments = SalePayment::where('sales_order_id',$order->id)
                                ->where('status',2)
                                ->whereNotIn('transaction_method', [4,5])
                                ->get();
                            foreach ($salePayments as $salePayment){
//                                if ($salePayment->transactionLog->payment_cheak_status != 3){
                                array_push($clientHistories,[
                                    'date'=>$order->date->format('d-m-Y') ?? '',
                                    'particular' => 'Receipt From'.' '.$order->customer->name.' '.$order->order_no??'',
                                    'quantity'=>0,
                                    'invoice'=>0,
                                    'discount'=>0,
                                    'transport_cost'=>0,
                                    'return'=> 0,
                                    'sale_adjustment'=> 0,
                                    'party_discount' => 0,
                                    'payment'=> $salePayment->amount,
                                    'due_balance'=>0,
                                ]);
//                                }elseif ($salePayment->transactionLog->payment_cheak_status == 3){
//                                    array_push($clientHistories,[
//                                        'date'=>$order->date->format('d-m-Y') ?? '',
//                                        'particular' => $salePayment->transactionLog->particular.' '.$order->order_no??'',
//                                        'quantity'=>0,
//                                        'invoice'=>0,
//                                        'return'=>0,
//                                        'payment'=> $salePayment->amount,
//                                        'due_balance'=>0,
//                                    ]);
//                                }
                            }
                        }else{
                            array_push($clientHistories,[
                                'date'=>$order->date->format('d-m-Y') ?? '',
                                'particular' => 'Invoice Total'.' '.$order->customer->name.' '.$order->order_no??'',
                                'quantity'=>$order->quantity() ??0,
                                'invoice'=>$order->sub_total,
                                'discount'=>$order->discount ?? '0',
                                'transport_cost'=>$order->transport_cost ?? '0',
                                'return'=> $order->return_amount??'0',
                                'sale_adjustment'=> $order->sale_adjustment??'0',
                                'party_discount' => 0,
                                'payment'=> 0,
                                'due_balance'=>$order->sub_total - $order->discount-$order->sale_adjustment+$order->transport_cost,

                            ]);


                            $salePayments = SalePayment::where('sales_order_id',$order->id)
                                ->where('status',2)
                                ->whereNotIn('transaction_method', [4,5])
                                ->get();

                            foreach ($salePayments as $salePayment) {
//                                if ($salePayment->transactionLog->payment_cheak_status != 3){
                                array_push($clientHistories, [
                                    'date' => $order->date->format('d-m-Y') ?? '',
                                    'particular' => 'Payment From' . ' ' . $order->customer->name . ' ' . $order->order_no ?? '',
                                    'quantity' => 0,
                                    'invoice' => 0,
                                    'discount'=>0,
                                    'transport_cost'=>0,
                                    'return'=> 0,
                                    'sale_adjustment'=> 0,
                                    'party_discount' => 0,
                                    'payment' => $salePayment->amount,
                                    'due_balance' => 0,
                                ]);
//                                }elseif ($salePayment->transactionLog->payment_cheak_status == 3){
//                                    array_push($clientHistories, [
//                                        'date' => $order->date->format('d-m-Y') ?? '',
//                                        'particular' => $salePayment->transactionLog->particular .' ' . $order->order_no ?? '',
//                                        'quantity' => 0,
//                                        'invoice' => 0,
//                                        'return' => 0,
//                                        'payment' => $salePayment->amount,
//                                        'due_balance' => 0,
//                                    ]);
//                                }
                            }
                        }
                    }

                    $payments = SalePayment::where('status',2)
                        ->where('customer_id',$request->client)
                        ->where('date',$searchDate)
                        ->where('sales_order_id','=',null)
                        ->get();

                    //dd($payments);
                    foreach ($payments as $payment){
                        if ($payment->transaction_method != 4 && $payment->transaction_method != 5){
                            array_push($clientHistories,[
                                'date'=>$payment->date->format('d-m-Y') ?? '',
                                // 'particular' => 'Receipt From'.' '.$payment->customer->name.' '.'Without Invoice',
                                'particular' => 'Receipt From'.' '.$payment->customer->name.' '.$payment->id??'',
                                'quantity'=>0,
                                'invoice'=>0,
                                'discount'=>0,
                                'transport_cost'=>0,
                                'return'=> 0,
                                'sale_adjustment'=> 0,
                                'party_discount' => 0,
                                'payment'=> $payment->amount,
                                'due_balance'=>0,
                            ]);
                        }
                    }

                    foreach ($payments as $payment){
                        if ($payment->transaction_method == 4) {
                            array_push($clientHistories,[
                                'date'=>$payment->date->format('d-m-Y') ?? '',
                                'particular'=>$payment->customer->name.'-'.'Balance Adjustment-'.$payment->id,
                                'quantity'=>0,
                                'invoice'=> 0,
                                'discount'=>0,
                                'transport_cost'=>0,
                                'return'=> 0,
                                'sale_adjustment'=> 0,
                                'party_discount' => 0,
                                'payment'=>$payment->amount,
                                'due_balance'=>0,
                            ]);
                        }
                    }

                    foreach ($payments as $payment) {
                        if($payment->transaction_method == 5) {
                            array_push($clientHistories, [
                                'date' => $payment->date->format('d-m-Y') ?? '',
                                'particular' => $payment->customer->name . '-' . 'Return Adjustment Amount-' . $payment->id,
                                'quantity' => 0,
                                'invoice' => 0,
                                'discount'=>0,
                                'transport_cost'=>0,
                                'return' => $payment->amount,
                                'sale_adjustment'=> 0,
                                'party_discount' => 0,
                                'payment' => 0,
                                'due_balance' => 0,
                            ]);
                        }
                    }

                }
            }


        }
        $partyDiscounts = PartyLess::where('customer_id', $request->client)->whereBetween('date', [$request->start, $request->end])->get();
        foreach ($partyDiscounts as $partyDiscount) {
//                                if ($salePayment->transactionLog->payment_cheak_status != 3){
            array_push($clientHistories, [
                'date'=> Carbon::parse($partyDiscount->date)->format('d-m-Y') ?? '',
                'particular' => 'Party Discount' . ' ' . $partyDiscount->customer->name,
                'quantity' => 0,
                'invoice' => 0,
                'discount' => 0,
                'transport_cost'=>0,
                'return' => 0,
                'sale_adjustment' => 0,
                'party_discount' => $partyDiscount->amount ?? '0',
                'payment' => 0,
                'due_balance' => 0,
            ]);
        }

//return($clientHistories);
        return view('report.party_ledger',compact('clients',
            'clientHistories','clientName','party_discount_amount'));
    }

    public function branchWiseSaleReturn(Request $request){

        $companyBranches = CompanyBranch::get();
        $productReturnOrders = [];

        if ($request->branch && $request->branch != ''
            && $request->start && $request->start != ''
            && $request->end && $request->end != '') {

            $customersId = Customer::where('company_branch_id',$request->branch)->pluck('id');
            $productReturnOrders = ProductReturnOrder::whereIn('customer_id',$customersId)
                ->get();
        }

        return view('report.branch_wise_sale_return',compact('productReturnOrders', 'companyBranches'));

    }

    public function subClientStatement(Request $request) {
        $report_type = $request->report_type??1;
        $customers = Customer::orderBy('name')->where('status',1)->get();
        $query = SubCustomer::orderBy('id', 'desc')->where('status', 1);
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }
        $subCustomers = $query->get();

        return view('report.sub_client_statement',compact('report_type', 'subCustomers', 'customers'));
    }

    public function supplierStatement(Request $request) {

        $allSuppliers = Supplier::where('status',1)->orderBy('id','desc')->get();
        if ($request->supplier != null) {
            $suppliers = Supplier::where('status',1)->where('id',$request->supplier)->orderBy('id','desc')->get();
        }else{
            $suppliers = Supplier::where('status',1)->orderBy('id','desc')->get();
        }

        return view('report.supplier_statement',compact('suppliers','allSuppliers'));
    }

    public function priceWithStock(Request $request) {
        $query = PurchaseInventory::where('quantity','>',0);
        $productItems = ProductCategory::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [$request->start, $request->end]);
            //$appends['date'] = $request->date;
        }

        if ($request->warehouse!='') {
            if ($request->warehouse=='all') {
                //$stocks=SaleProduct::where('status',1)->get();
            }else {
                $query->where('warehouse_id', $request->warehouse);

            }
        }

        if ($request->product_item!='') {
            if ($request->product_item=='all') {
                //$stocks=SaleProduct::where('status',1)->get();
            }else {
                $query->where('product_category_id', $request->product_item);

            }
        }

        $inventories = $query->with('warehouse', 'productCategory')->get();

        return view('report.price_with_stock',compact('inventories', 'productItems','warehouses'));
    }

    public function priceWithOutStock(Request $request) {
        $query = PurchaseInventory::query();
        $products = Product::orderBy('name')->get();

        if ($request->product!='') {
            if ($request->product=='all') {
                //$stocks=SaleProduct::where('status',1)->get();
            }else {
                $query->where('product_id', $request->product);

            }
        }

        $inventories = $query->with('product', 'warehouse')->get();

        return view('report.price_without_stock',compact('products','inventories'));
    }
    public function receivePayment(Request $request){

        $incomes = null;
        $expenses = null;
        $incomeQuery = TransactionLog::query();
        $expenseQuery = TransactionLog::query();

        $incomeQuery->where('transaction_type', 1);
        $expenseQuery->where('transaction_type', 2);
        $incomeQuery->select(DB::raw('sum(amount) as amount, account_head_type_id'));
        $expenseQuery->select(DB::raw('sum(amount) as amount, account_head_type_id'));
        $incomeQuery->where('account_head_type_id','!=', 0);
        $expenseQuery->where('account_head_type_id','!=', 0);

        if ($request->account_head_type != '') {
            $incomeQuery->where('account_head_type_id', $request->account_head_type);
            $expenseQuery->where('account_head_type_id', $request->account_head_type);
        }

        if ($request->start != '') {
            $incomeQuery->where('date', '>=', $request->start);
            $expenseQuery->where('date', '>=', $request->start);
        }

        if ($request->end != '') {
            $incomeQuery->where('date', '<=', $request->end);
            $expenseQuery->where('date', '<=', $request->end);
        }

        $incomeQuery->groupBy('account_head_type_id');
        $expenseQuery->groupBy('account_head_type_id');

        $incomes = $incomeQuery->get();
        $expenses = $expenseQuery->get();
        return view('report.receive_payment',compact('incomes','expenses'));

    }

    public function trailBalance(Request $request){

        $incomes = null;
        $expenses = null;
        $incomeQuery = TransactionLog::query();
        $expenseQuery = TransactionLog::query();

        $incomeQuery->where('transaction_type', 1);
        $expenseQuery->where('transaction_type', 2);
        $incomeQuery->select(DB::raw('sum(amount) as amount, account_head_type_id'));
        $expenseQuery->select(DB::raw('sum(amount) as amount, account_head_type_id'));
        $incomeQuery->where('account_head_type_id','!=', 0);
        $expenseQuery->where('account_head_type_id','!=', 0);

        if ($request->account_head_type != '') {
            $incomeQuery->where('account_head_type_id', $request->account_head_type);
            $expenseQuery->where('account_head_type_id', $request->account_head_type);
        }

        if ($request->start != '') {
            $incomeQuery->where('date', '>=', $request->start);
            $expenseQuery->where('date', '>=', $request->start);
        }

        if ($request->end != '') {
            $incomeQuery->where('date', '<=', $request->end);
            $expenseQuery->where('date', '<=', $request->end);
        }

        $incomeQuery->groupBy('account_head_type_id');
        $expenseQuery->groupBy('account_head_type_id');

        $incomes = $incomeQuery->get();
        $expenses = $expenseQuery->get();
        return view('report.trail_balance',compact('incomes','expenses'));
    }

    public function employeeList(Request $request)
    {
        if ($request->category != '') {
            $employees = Employee::with('designation', 'department')
            ->where('category_id', $request->category)
                ->get();
        } else {
            $employees = Employee::with('designation', 'department')->get();
        }

        return view('report.employee_list', compact('employees'));
    }

    public function monthlyCRM(Request $request){
        $clients = null;
        $year = $request->year;
        $month = $request->month;
        if($year !='' && $month !=''){
            $clients = ClientManagement::groupBy('marketing_id')->get();
        }

        return view('report.monthly_crm',compact('clients','year','month'));
    }

    public function productInOut(Request $request){
        $productItems = ProductItem::orderBy('name')->get();
        $productItem = ProductItem::find($request->product_item_id)??0;
        $results = null;
        $product_item_id = $request->product_item_id;
        $type = $request->type??1;
        $query = PurchaseInventoryLog::with('purchaseOrder','saleOrder')
            ->where('product_item_id', $product_item_id);

        if ($type != 3) {
            $query->where('type', $type)->where('return_status', 0);
        }

        if ($type == 3) {
            $query->where('type', 1)->where('return_status', 1);
        }

        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
        }

        $results = $query->get();
        return view('report.product_in_out', compact('results', 'productItems', 'productItem','type'));
    }

    public function transaction(Request $request) {
        $result = null;
        $types = AccountHeadType::whereNotIn('id', [1, 2, 3, 4,209,210])->get();
        //$subTypes = AccountHeadSubType::whereNotIn('id', [1, 2, 3, 4,210,209])->get();

        if ($request->start && $request->end) {
            $query = TransactionLog::query();
            $query->select(DB::raw('sum(amount) as amount, account_head_type_id, account_head_sub_type_id, sale_type_status'));
            $query->whereBetween('date', [$request->start, $request->end]);
            $query->whereNotIn('account_head_type_id', [0, 1, 2, 3, 4, 210, 209]);
            $query->where('sale_type_status', $request->sale_type);
            //$query->whereNotIn('account_head_sub_type_id', [0, 1, 2, 3, 4, 210, 209]);

            if ($request->type && $request->type != '')
                $query->where('account_head_type_id', $request->type);

//            if ($request->sub_type && $request->sub_type != '')
//                $query->where('account_head_sub_type_id', $request->sub_type);

            $query->groupBy( 'account_head_type_id');
            $query->with('accountHead');

            $result = $query->get();
            //dd($result);
        }

        return view('report.transaction', compact('result', 'types'));
    }
    public function transfer(Request $request){
        $branches = CompanyBranch::where('status',1)->get();
        $result = null;
        if ($request->start && $request->end) {
            $query = BalanceTransfer::query();
            if($request->company_branch == 0){
                $query->whereBetween( 'date',[$request->start, $request->end]);
                $query->orderBy( 'date','desc');
                $query->with('sourchBranch','targetBranch','sourceBankAccount','targetBankAccount');

            }else{
                $query->whereBetween( 'date',[$request->start, $request->end]);
                $query->where( 'source_com_branch_id',$request->company_branch);
                $query->orWhere( 'target_com_branch_id',$request->company_branch);
                $query->orderBy( 'date','desc');
                $query->with('sourchBranch','targetBranch','sourceBankAccount','targetBankAccount');

            }

            $result = $query->get();
        }
        return view('report.transfer', compact('result','branches'));
    }
    public function partyLess(Request $request)
    {
        if (Auth::user()->company_branch_id == 0) {
            $clients = Customer::where('status',1)->orderBy('name')->get();
        }else{
            $clients = Customer::where('status',1)
                ->where('company_branch_id',Auth::user()->company_branch_id)
                ->get();
        }
        $partyInfo =null;
        $partyLesses =null;

        if(($request->client  != '') &&  ($request->start != '') &&  ($request->end != '')) {
            $partyLesses = PartyLess::where('customer_id',$request->client)->whereBetween('date', [$request->start, $request->end])->get();
            $partyInfo = Customer::find($request->client);
        }

        return view('report.party_less',compact('clients', 'partyLesses','partyInfo'));
    }

    public function adjustment(Request $request) {


            $customers = Customer::orderBy('name')->where('status', 1)->get();
            $appends = [];

            $query = SalesOrder::query();


        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
            $appends['start'] = $request->start;
            $appends['end'] = $request->end;
        }else{
            $query->whereBetween('date', [date('Y-m-d'), date('Y-m-d')]);
            $appends['start'] = date('Y-m-d');
            $appends['end'] = date('Y-m-d');
        }

        if ($request->customer && $request->customer != '') {
            $query->where('customer_id', $request->customer);
            $appends['customer'] = $request->customer;
        }



        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        $query->with('products');
        $orderTotalAmount = 0;
        $orderPaidAmount = 0;
        $orderDueAmount = 0;
        $totalQuantity = 0;

        $totalOrders = $query->get();
        foreach ($totalOrders as $totalOrder){
            $orderTotalAmount += $totalOrder->total;
            $orderPaidAmount += $totalOrder->paid;
            $orderDueAmount += $totalOrder->due;
            $totalQuantity+=$totalOrder->quantity();
        }

        $orders = $query->paginate(10);

        foreach ($orders as $order) {
            $orderProducts = [];

            foreach ($order->products as $orderProduct)
                $orderProducts[] = $orderProduct->productItem->name??''.' - '.$orderProduct->pivot->product_name??'';

            $order->product_name = implode(', ', $orderProducts);
        }

        return view('report.adjustment', compact('customers', 'products',
            'appends', 'orders', 'suppliers','product_items','branches','orderTotalAmount','orderPaidAmount','orderDueAmount','totalQuantity'));
    }

}
