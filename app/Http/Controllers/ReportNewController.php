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
use App\Model\SalesOrderProduct;
use App\Model\SubCustomer;
use App\Model\Supplier;
use App\Model\Transaction;
use App\Model\TransactionLog;
use App\Model\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportNewController extends Controller
{
    public function itemWiseStock(Request $request) {
        $query = PurchaseInventory::where('quantity','>',0);
        $productItems = ProductItem::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        //dd($productItems);
        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [$request->start, $request->end]);
        }

        if ($request->product_item!='') {
            if ($request->product_item=='all') {
                //$stocks=SaleProduct::where('status',1)->get();
            }else {
                $query->where('product_item_id', $request->product_item);
            }
        }

        $inventories = $query->with('warehouse', 'productCategory')->get();

        return view('report_new.item_wise_stock',compact('inventories', 'productItems','warehouses'));
    }
    public function companyWiseStock(Request $request) {
        $query = DB::table('purchase_inventories')->where('quantity','>',0)
            ->join('product_items','product_items.id','=','purchase_inventories.product_item_id')
            ->join('suppliers','suppliers.id','=','product_items.supplier_id');
        $suppliers = Supplier::where('status',1)->orderBy('name')->get();
        //dd($productItems);
//        if ($request->start && $request->end) {
//            $query->whereBetween('created_at', [$request->start, $request->end]);
//        }

        if ($request->company!='') {
            if ($request->company=='all') {
                //$stocks=SaleProduct::where('status',1)->get();
            }else {
                $query->where('product_items.supplier_id', $request->company);
            }
        }

        $inventories = $query->orderBy('product_items.supplier_id','asc')
            ->select('purchase_inventories.*','product_items.name as item_name','suppliers.name as supplier_name','product_items.supplier_id')
            ->get();
        //dd($inventories);
        return view('report_new.company_wise_stock',compact('inventories','suppliers'));
    }
    public function totalStock(Request $request) {
        $query = PurchaseInventory::where('quantity','>',0);
        $productItems = ProductItem::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        //dd($productItems);
        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [$request->start, $request->end]);
        }

        if ($request->product_item!='') {
            if ($request->product_item=='all') {
                //$stocks=SaleProduct::where('status',1)->get();
            }else {
                $query->where('product_item_id', $request->product_item);
            }
        }

        $inventories = $query->with('warehouse', 'productCategory')->get();

        return view('report_new.total_stock',compact('inventories', 'productItems','warehouses'));
    }
    public function productWiseSale(Request $request) {
        $query = SalesOrderProduct::query();
        $productItems = ProductItem::orderBy('name')->get();

        if ($request->start && $request->end) {
            $query->whereBetween('sales_order_products.created_at', [$request->start, $request->end]);
        }

        if ($request->product_item!='') {
            if ($request->product_item=='all') {
                //$stocks=SaleProduct::where('status',1)->get();
            }else {
                $query->where('product_item_id', $request->product_item);
            }
        }

        $orderProducts = $query->join('sales_orders','sales_orders.id','=','sales_order_products.sales_order_id')
            ->where('sales_orders.type',$request->sale_type)
            ->groupBy('product_item_id','product_items.name')
            ->join('product_items','product_items.id','=','sales_order_products.product_item_id')
//            ->join('sales_orders','sales_orders.id','=','sales_order_products.sales_order_id')
            ->selectRaw('product_item_id,product_items.name,SUM(quantity) as quantity,SUM(buy_price) as buy_price')
            ->get();
        //dd($orderProducts);
        return view('report_new.product_wise_sale',compact('orderProducts', 'productItems'));
    }
    public function totalSale(Request $request){
        $query = SalesOrder::where('type',$request->sale_type);
        $productItems = ProductItem::orderBy('name')->get();

        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
        }

        $dateWiseSales = $query->groupBy('date')
            //->join('product_items','product_items.id','=','sales_order_products.product_item_id')
            ->selectRaw('date,COUNT(id) as totalDaySale,SUM(paid) as totalPaid,SUM(discount) as totalDiscount,SUM(due) as totalDue,SUM(total) as grandTotal')
            ->get();
        //dd($dateWiseSales);
        return view('report_new.total_sale',compact('dateWiseSales', 'productItems'));
    }
    public function partyWiseSale(Request $request) {
        $query = SalesOrderProduct::query();
        $customers = Customer::orderBy('name')->get();
        $orderProducts = [];

        if ($request->start && $request->end) {
            $query->whereBetween('sales_orders.date', [$request->start, $request->end]);
        }

        if ($request->customer !='') {
            //dd('dd');
            $query->where('sales_orders.customer_id', $request->customer);
            $orderProducts = $query->join('sales_orders','sales_orders.id','=','sales_order_products.sales_order_id')
                ->where('sales_orders.type',$request->sale_type)
                ->groupBy('sales_order_products.product_item_id')
                ->selectRaw('sales_order_products.product_item_id,SUM(sales_order_products.quantity) as quantity,SUM(sales_order_products.total) as total')
                ->get();
        }

        //dd($orderProducts);
        return view('report_new.party_wise_sale',compact('orderProducts', 'customers'));
    }
    public function purchase(Request $request){
        $query = PurchaseOrder::query();
        $suppliers = Supplier::orderBy('name')->get();

        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
        }

        $dateWisePurchases = $query->groupBy('date')
            //->join('product_items','product_items.id','=','sales_order_products.product_item_id')
            ->selectRaw('date,COUNT(id) as totalDaySale,SUM(paid) as totalPaid,SUM(discount) as totalDiscount,SUM(due) as totalDue,SUM(total) as grandTotal')
            ->get();
        //dd($dateWiseSales);
        return view('report_new.purchase',compact('dateWisePurchases', 'suppliers'));
    }

     public function partyLedger(Request $request){
//        if (Auth::user()->company_branch_id == 0) {
//            $clients = Customer::where('status',1)->orderBy('name')->get();
//            $clientName = '';
//            $clientHistories = [];
//        }else{
//            $clients = Customer::where('status',1)
//                ->where('company_branch_id',Auth::user()->company_branch_id)
//                ->get();
//            $clientName = '';
//            $clientHistories = [];
//        }
         $clients = Customer::where('status',1)->orderBy('name')->get();
         $clientName = '';
         $clientHistories = [];

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
                                'payment'=> 0,
                                'due_balance'=>$order->sub_total - $order->discount-$order->sale_adjustment+$order->transport_cost,
                            ]);


                            $salePayments = SalePayment::where('sales_order_id',$order->id)->where('status',2)->get();
                            foreach ($salePayments as $salePayment){
                                array_push($clientHistories,[
                                    'date'=>$order->date->format('d-m-Y') ?? '',
                                    'particular' => 'Payment From'.' '.$order->customer->name.' '.$order->order_no??'',
                                    'quantity'=>0,
                                    'invoice'=>0,
                                    'discount'=>0,
                                    'transport_cost'=>0,
                                    'return'=> 0,
                                    'payment'=> $salePayment->amount,
                                    'due_balance'=>0,
                                ]);
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
                                'payment'=> 0,
                                'due_balance'=>$order->sub_total - $order->discount-$order->sale_adjustment+$order->transport_cost,

                            ]);


                            $salePayments = SalePayment::with('transactionLog')->where('sales_order_id',$order->id)->where('status',2)->get();


                            foreach ($salePayments as $salePayment){
                                array_push($clientHistories,[
                                    'date'=>$order->date->format('d-m-Y') ?? '',
                                    'particular' => 'Payment From'.' '.$order->customer->name.' '.$order->order_no??'',
                                    'quantity'=>0,
                                    'invoice'=>0,
                                    'discount'=>0,
                                    'transport_cost'=>0,
                                    'return'=> 0,
                                    'payment'=> $salePayment->amount,
                                    'due_balance'=>0,
                                ]);
                            }
                        }
                    }


                    $payments = SalePayment::where('status',2)
                        ->where('customer_id',$request->client)
                        ->whereBetween('date', [$request->start, $request->end])
                        ->where('date',$searchDate)
                        ->where('sales_order_id','=',null)
                        ->get();

                    foreach ($payments as $payment) {
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
                                'payment' => $payment->amount,
                                'due_balance' => 0,
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
                                'payment' => 0,
                                'due_balance' => 0,
                            ]);
                        }
                    }
                }
            }

        }


        return view('report_new.party_ledger',compact('clients',
            'clientHistories','clientName'));
    }
    public function supplierLedger(Request $request){

        $suppliers = Supplier::where('status',1)->orderBy('name')->get();
        $supplierName = '';
        $supplierHistories = [];

        if ($request->supplier && $request->supplier != ''
            && $request->start && $request->start != ''
            && $request->end && $request->end != '') {

            $startDateArray = [];
            $endDateArray = [];

            $supplierName = $openingDue = Supplier::where('id',$request->supplier)
                ->first();

            $firstOrder = PurchaseOrder::where('supplier_id',$request->supplier)
                ->orderBy('date','asc')
                ->first();

            $lastOrder = PurchaseOrder::where('supplier_id',$request->supplier)
                ->orderBy('date','desc')
                ->first();

            $firstPayment = PurchasePayment::where('supplier_id',$request->supplier)
                ->orderBy('date','asc')
                ->first();

            $lastPayment = PurchasePayment::where('supplier_id',$request->supplier)
                ->orderBy('date','desc')
                ->first();

            $firstReturn = PurchaseOrder::where('supplier_id',$request->supplier)
                ->orderBy('date','asc')
                ->first();

            $lastReturn = PurchaseOrder::where('supplier_id',$request->supplier)
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

                $supplierHistories = [];
                array_push($supplierHistories,[
                    'date'=>$openingDue->created_at->format('d-m-Y') ?? '',
                    'particular'=>'Opening Balance',
                    'quantity'=>0,
                    'invoice'=>0,
                    'discount'=>0,
                    'transport_cost'=>0,
                    'payment'=>0,
                    'due_balance'=>$openingDue->opening_due,
                ]);

                $orderPayment = [];

                for ($i = 0; $i < $totalDurationLengths;$i++) {
                    $date = Carbon::createFromFormat('d-m-Y',$startMin);
                    $searchDate = $date->addDays($i)->format('Y-m-d');

                    $orders = PurchaseOrder::where('supplier_id',$request->supplier)
                        ->whereBetween('date', [$request->start, $request->end])
                        ->where('date',$searchDate)->get();

                    foreach ($orders as $order){
                        if ($order->total <= 0 || $order->discount > 0 ) {
                            array_push($supplierHistories,[
                                'date'=>$order->date->format('d-m-Y') ?? '',
                                'particular' => 'Invoice Total'.' '.$order->supplier->name.' '.$order->order_no??'',
                                'quantity'=>$order->quantity() ??0,
                                'invoice'=>$order->total,
                                'discount'=>$order->discount ?? '0',
                                'transport_cost'=>$order->transport_cost ?? '0',
                                'payment'=> 0,
                                'due_balance'=>$order->total,
                            ]);


                            $purchasePayments = PurchasePayment::where('purchase_order_id',$order->id)->get();
                            foreach ($purchasePayments as $purchasePayment){
                                array_push($supplierHistories,[
                                    'date'=>$order->date->format('d-m-Y') ?? '',
                                    'particular' => 'Payment From'.' '.$order->supplier->name.' '.$order->order_no??'',
                                    'quantity'=>0,
                                    'invoice'=>0,
                                    'discount'=>0,
                                    'transport_cost'=>0,
                                    'payment'=> $purchasePayment->amount,
                                    'due_balance'=>0,
                                ]);
                            }

                        }else{

                            array_push($supplierHistories,[
                                'date'=>$order->date->format('d-m-Y') ?? '',
                                'particular' => 'Invoice Total'.' '.$order->supplier->name.' '.$order->order_no??'',
                                'quantity'=>$order->quantity() ??0,
                                'invoice'=>$order->total,
                                'discount'=>$order->discount ?? '0',
                                'transport_cost'=>$order->transport_cost ?? '0',
                                'payment'=> 0,
                                'due_balance'=>$order->total,

                            ]);


                            $purchasePayments = PurchasePayment::with('transactionLog')->where('purchase_order_id',$order->id)->get();


                            foreach ($purchasePayments as $purchasePayment){
                                array_push($supplierHistories,[
                                    'date'=>$order->date->format('d-m-Y') ?? '',
                                    'particular' => 'Payment From'.' '.$order->supplier->name.' '.$order->order_no??'',
                                    'quantity'=>0,
                                    'invoice'=>0,
                                    'discount'=>0,
                                    'transport_cost'=>0,
                                    'payment'=> $purchasePayment->amount,
                                    'due_balance'=>0,
                                ]);
                            }
                        }
                    }


                    $payments = PurchasePayment::where('supplier_id',$request->supplier)
                        ->whereBetween('date', [$request->start, $request->end])
                        ->where('date',$searchDate)
                        ->where('purchase_order_id','=',null)
                        ->get();

                    foreach ($payments as $payment) {
                        if ($payment->transaction_method != 4 && $payment->transaction_method != 5){
                            array_push($supplierHistories, [
                                'date' => $payment->date->format('d-m-Y') ?? '',
                                'particular' => 'Receipt From' . ' ' . $payment->supplier->name . ' ' . $payment->id?? '',
                                'quantity' => 0,
                                'invoice' => 0,
                                'discount'=>0,
                                'transport_cost'=>0,
                                'payment' => $payment->amount,
                                'due_balance' => 0,
                            ]);
                        }
                    }
                }
            }

        }


        return view('report_new.supplier_ledger',compact('suppliers','supplierHistories','supplierName'));
    }

}
