<?php

namespace App\Http\Controllers;

use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\BranchCash;
use App\Model\Cash;
use App\Model\CompanyBranch;
use App\Model\Customer;
use App\Model\MobileBanking;
use App\Model\PartyLess;
use App\Model\ProductItem;
use App\Model\ProductReturnOrder;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\SalePayment;
use App\Model\SalesOrder;
use App\Model\Service;
use App\Model\Supplier;
use App\Model\Transaction;
use App\Model\TransactionLog;
use App\Model\Warehouse;
use App\Model\Product;
use App\Model\ProductSalesOrder;
use App\Model\SalesOrderProduct;
use App\ReturnPaidLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Rakibhstu\Banglanumber\NumberToBangla;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use DB;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    public function retailSaleOrder() {
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        if (Auth::user()->company_branch_id == 0) {
            $customers = Customer::where('status', 1)->with('branch')->orderBy('name')->get();
        }else{
            $customers = Customer::where('status', 1)->where('company_branch_id',Auth::user()->company_branch_id)->orderBy('name')->get();
        }
        $bankAccounts = BankAccount::where('status', 1)->get();
        $productItems = ProductItem::where('status', 1)->orderBy('name')->get();
        $companyBranches = CompanyBranch::where('status', 1)->orderBy('name')->get();
//        if (Auth::user()->company_branch_id == 0) {
//            $sale_orders = SalesOrder::where('type',1)->orderBy('id','desc')->take(10)->get();
//        }else{
//            $sale_orders = SalesOrder::where('type',1)->where('company_branch_id',Auth::user()->company_branch_id)->orderBy('id','desc')->take(10)->get();
//        }
        $sale_orders = SalesOrder::where('type',1)->orderBy('id','desc')->take(10)->get();
        $companies = Supplier::where('status',1)->get();

        return view('sale.sales_order.create_retail_sale', compact('warehouses', 'bankAccounts',
            'productItems','customers','companyBranches','sale_orders','companies'));
    }
    public function wholeSaleOrder() {
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        if (Auth::user()->company_branch_id == 0) {
            $customers = Customer::where('status', 1)->with('branch')->orderBy('name')->get();
        }else{
            $customers = Customer::where('status', 1)->where('company_branch_id',Auth::user()->company_branch_id)->orderBy('name')->get();
        }
        $bankAccounts = BankAccount::where('status', 1)->get();
        $productItems = ProductItem::where('status', 1)->orderBy('name')->get();
        $companyBranches = CompanyBranch::where('status', 1)->orderBy('name')->get();
//        if (Auth::user()->company_branch_id == 0) {
//            $sale_orders = SalesOrder::where('type',2)->orderBy('id','desc')->take(10)->get();
//        }else{
//            $sale_orders = SalesOrder::where('type',2)->where('company_branch_id',Auth::user()->company_branch_id)->orderBy('id','desc')->take(10)->get();
//        }
        $sale_orders = SalesOrder::where('type',2)->orderBy('id','desc')->take(10)->get();
        $companies = Supplier::where('status',1)->get();

        return view('sale.sales_order.create_whole_sale', compact('warehouses', 'bankAccounts',
            'productItems','customers','companyBranches','sale_orders','companies'));
    }
    public function salesOrderNew() {
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        if (Auth::user()->company_branch_id == 0) {
            $customers = Customer::where('status', 1)->with('branch')->orderBy('name')->get();
        }else{
            $customers = Customer::where('status', 1)->where('company_branch_id',Auth::user()->company_branch_id)->orderBy('name')->get();
        }
        $bankAccounts = BankAccount::where('status', 1)->get();
        $productItems = ProductItem::where('status', 1)->orderBy('name')->get();
        $companyBranches = CompanyBranch::where('status', 1)->orderBy('name')->get();
        if (Auth::user()->company_branch_id == 0) {
            $saleOrders = SalesOrder::orderBy('id','desc')->take(10)->get();
        }else{
            $saleOrders = SalesOrder::where('company_branch_id',Auth::user()->company_branch_id)->orderBy('id','desc')->take(10)->get();
        }
//        $checkSaleOrder = SalesOrder::where('company_branch_id',Auth::user()->company_branch_id)->orderBy('id','desc')->first();
//        if ($checkSaleOrder){
//            $order_no = (int)$checkSaleOrder->order_no + 1;
//        }else{
//            $order_no = 1;
//        }
//        $orderNo = str_pad($order_no, 6, 0, STR_PAD_LEFT);
        return view('sale.sales_order.create_new', compact('warehouses', 'bankAccounts',
            'productItems','customers','companyBranches','saleOrders'));
    }

    public function retailSaleOrderPost(Request $request){
        //return($request->all());
        if (empty($request->product_serial)) {
            $message = 'No Product Item Found';
            return redirect()->back()->withInput()->with('message', $message);
        }

        $total = $request->total;
        $due = $request->due_total;
        $rules = [
            'date' => 'required|date',
            'customer' => 'required|numeric',
        ];

        if (Auth::user()->company_branch_id == 0) {
            $rules['companyBranch'] = 'required';
        }
        if ($request->product_serial) {
            $rules['paid'] = 'required|numeric|min:0';
            $rules['invoice_type'] = 'required';
            $rules['product_serial.*'] = 'required';
            $rules['product_item.*'] = 'required';
            $rules['product_category.*'] = 'required';
            $rules['product_color.*'] = 'required';
            $rules['product_size.*'] = 'required';
            $rules['warehouse.*'] = 'required';
            $rules['quantity.*'] = 'required|numeric|min:.01';
            $rules['unit_price.*'] = 'required|numeric|min:0';
        }


        if ($due > 0)
            $rules['next_payment'] = 'nullable|date';

        if ($request->payment_type == '3') {
            $rules['client_bank_name'] = 'required';
            $rules['client_cheque_no'] = 'required';
            $rules['cheque_date'] = 'required';
            $rules['cheque_amount'] = 'required';
        }
        if ($request->payment_type == '2') {
            $rules['account_no'] = 'required';
        }

        $request->validate($rules);

        $returnOrder=ProductReturnOrder::where('customer_id',$request->customer)->where('status',0)->first();
        if($returnOrder){
            $returnProfit = $returnOrder->profit;
            $returnOrder->status=1;
            $returnOrder->save();
        }else{
            $returnProfit = 0;
        }

        $available = true;
        $message = '';

         if ($request->purchase_inventory) {
                foreach ($request->purchase_inventory as $index => $purchase_inventory_id) {
                    $inventory = PurchaseInventory::find($purchase_inventory_id);
                    if ($inventory) {
                        if ($request->quantity[$index] > $inventory->quantity) {
                            $available = false;
                            $message = 'Insufficient ' . $inventory->serial;
                            break;
                        }
                    }
                }
            }
         if ($request->payment_type == 3 && $request->paid > 0) {
             $available = false;
             $message = 'Paid must be zero(0)';
            }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }



        $order = new SalesOrder();
//        if (Auth::user()->company_branch_id == 0) {
//            $order->company_branch_id = $request->companyBranch;
//        }else{
//            $order->company_branch_id = Auth::user()->company_branch_id;
//        }
        $order->company_branch_id = 1;
        $order->type = $request->type;
        $order->invoice_type = $request->invoice_type;
        $order->customer_id = $request->customer;
//        $order->received_by = $request->received_by;
        $order->date = $request->date;
        $order->note = $request->note;
        $order->sub_total = 0;
        $order->vat_percentage = $request->vat;
        $order->vat = 0;
        $order->discount_percentage = $request->discount_percentage;
        $order->discount = $request->discount;
        $order->transport_cost = $request->transport_cost;
        $order->return_amount = $request->return_amount??0;
        $order->sale_adjustment = $request->sale_adjustment??0;
        $order->sale_type = $request->sale_type;
        $order->total = 0;
        $order->paid = $request->paid;
        if ($request->payment_type == 3){
            $order->client_bank_name = $request->client_bank_name;
            $order->client_cheque_no = $request->client_cheque_no;
            $order->cheque_date = $request->cheque_date;
            $order->client_amount = $request->cheque_amount;
        }
        $order->due = 0;
        $order->previous_due = $request->previous_due;
        $order->current_due = $request->due_total;
        $order->user_id = Auth::user()->id;

        $branch_id = 1;
        $checkSaleOrder = SalesOrder::where('type',1)->where('company_branch_id',$branch_id)->orderBy('id','desc')->first();
        if ($checkSaleOrder){
            $order_no = (int)$checkSaleOrder->order_no + 1;
        }else{
            $order_no = 1;
        }
        $order->order_no = str_pad($order_no, 6, 0, STR_PAD_LEFT);
        $order->save();

        $subTotal = 0;
        $invoiceProfit = 0;
        if  ($request->purchase_inventory) {
            foreach ($request->purchase_inventory as $key => $purchase_inventory_id) {
                $inventory = PurchaseInventory::find($purchase_inventory_id);
                //dd($inventory);
                if ($inventory) {
                    $sales_order_product = SalesOrderProduct::where('sales_order_id', $order->id)->where('purchase_inventory_id', $purchase_inventory_id)->first();
                    if (empty($sales_order_product)) {
                        SalesOrderProduct::create([
                            'sales_order_id' => $order->id,
                            'purchase_inventory_id' => $purchase_inventory_id,
                            'product_item_id' => $inventory->product_item_id,
                            'product_category_id' => $inventory->product_category_id,
                            'warehouse_id' => $inventory->warehouse_id,
                            'serial' => $inventory->serial,
                            'quantity' => $request->quantity[$key],
                            'buy_price' => $inventory->unit_price,
                            'unit_price' => $request->unit_price[$key],
                            'total' => $request->quantity[$key] * $request->unit_price[$key],
                        ]);

                            // Inventory Log
                            $inventoryLog = PurchaseInventoryLog::where('sales_order_id', $order->id)
                                ->where('purchase_inventory_id', $purchase_inventory_id)
                                ->where('type', 2)->first();
                            if (empty($inventoryLog)) {
                                $inventoryLog = new PurchaseInventoryLog();
                            }

                            $inventoryLog->product_item_id = $inventory->product_item_id;
                            $inventoryLog->product_category_id = $inventory->product_category_id;
                            $inventoryLog->warehouse_id = $inventory->warehouse_id;
                            $inventoryLog->serial = $inventory->serial;
                            $inventoryLog->type = 2;
                            $inventoryLog->date = $request->date;
                            $inventoryLog->quantity = $request->quantity[$key];
                            $inventoryLog->unit_price = $inventory->unit_price;
                            $inventoryLog->selling_price = $request->unit_price[$key];
                            $inventoryLog->wholesale_price = $inventory->wholesale_price;
                            $inventoryLog->sale_total = $request->quantity[$key] * $request->unit_price[$key];
                            $inventoryLog->total = $request->quantity[$key] * $inventory->unit_price;
                            $inventoryLog->sales_order_id = $order->id;
                            $inventoryLog->purchase_inventory_id = $inventory->id;
                            $inventoryLog->note = 'Sale Product';
                            $inventoryLog->user_id = Auth::id();
                            $inventoryLog->save();

                        $subTotal += $request->quantity[$key] * $request->unit_price[$key];
                        $invoiceProfit += ($request->quantity[$key] * $request->unit_price[$key])-($inventory->avg_unit_price*$request->quantity[$key]);

                            $inventory->decrement('quantity', $request->quantity[$key]);
                        // $inventory->update([
                        //     'quantity' => $inventory->in_product - $inventory->out_product,
                        // ]);
                    }

                }

            }
        }

        $order->sub_total = $subTotal;
        $vat = ($subTotal * $request->vat) / 100;
        $order->vat = $vat;
        $total = $subTotal + $request->transport_cost + $vat - $request->discount - $request->return_amount - $request->sale_adjustment;
        $order->total = $total;
        $order->invoice_total = $total > $request->paid ? $request->paid : $total;
        if ($request->paid > $total){
            $due = 0;
        }else{
            $due = $total - $request->paid;
        }
        $order->due = $due;
        $order->invoice_profit = round($invoiceProfit-$request->discount-$returnProfit);
        $order->next_payment = $due > 0 ? $request->next_payment??'' : null;
        $order->save();

        //Net profit log
        $log = new TransactionLog();
        $log->date = $request->date;
        $log->particular = 'Receive from '.($order->order_no).' '.$order->customer->name;
        $log->transaction_type = 1;
        $log->transaction_method = 1;
        $log->account_head_type_id = 2;
        $log->account_head_sub_type_id = 2;
        $log->amount =  round($invoiceProfit-$request->discount-$returnProfit);
        $log->company_branch_id = $order->company_branch_id;
        $log->sale_type_status = 1;
        $log->net_profit = 1;
        $log->save();

        //Return paid log

        if($request->return_amount > 0){
            $returnPaid = new ReturnPaidLog();
            $returnPaid->customer_id = $request->customer;
            $returnPaid->sales_order_id = $order->id;
            $returnPaid->product_return_order_id = $returnOrder->id;
            $returnPaid->amount = $request->return_amount;
            $returnPaid->date = $request->date;
            $returnPaid->save();
        }

        // Sales Payment
        if ($request->paid > 0) {
            if ($request->payment_type == 1){
                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->customer_id = $request->customer;
                $payment->sale_type_status = 1;
                $payment->company_branch_id = $order->company_branch_id;
                $payment->transaction_method = 1;
                $payment->received_type = 1;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->status = 2;
                $payment->save();

                Cash::first()->increment('amount', $request->paid);
                //BranchCash::where('company_branch_id',$order->company_branch_id)->first()->increment('amount', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Rev. from '.($order->order_no).' '.$order->customer->name;
                $log->transaction_type = 1;
                $log->transaction_method = 1;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->amount =  $request->paid;
                $log->customer_id = $request->customer??null;
                $log->sale_type_status = 1;
                $log->sale_payment_id = $payment->id;
                $log->company_branch_id = $order->company_branch_id;
                $log->sales_order_id = $order->id;
                $log->save();
            }
            if ($request->payment_type == 2) {

                $bankAccount = BankAccount::find($request->account_no);

                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->customer_id = $request->customer;
                $payment->sale_type_status = 1;
                $payment->company_branch_id = $order->company_branch_id;
                $payment->transaction_method= 2;
//                $payment->client_bank_name = $request->client_bank_name;
//                $payment->client_cheque_no = $request->client_cheque_no;
//                $payment->client_amount = $request->paid;
                $payment->bank_id = $bankAccount->bank_id;
                $payment->branch_id = $bankAccount->branch_id;
                $payment->bank_account_id = $bankAccount->id;
                $payment->cheque_no = $request->cheque_no;
//                $payment->cheque_image = $image;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->cheque_date = $request->cheque_date;
                $payment->note = $request->note;
                $payment->status = 2;
                $payment->save();

                BankAccount::find($request->account_no)->increment('balance', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Rev. from '.($order->order_no).' '.$order->customer->name;
                $log->transaction_type = 1;
                $log->transaction_method = 2;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->bank_id = $bankAccount->bank_id;
                $log->branch_id = $bankAccount->branch_id;
                $log->bank_account_id = $bankAccount->id;
//                $log->cheque_no = $request->cheque_no;
//                $log->cheque_image = $image;
                $log->amount = $request->paid;
                $log->customer_id = $request->customer ?? null;
                $log->sale_type_status = 1;
                $log->company_branch_id = $order->company_branch_id;
                $log->sales_order_id = $order->id;
                $log->sale_type_status = $payment->id;
                $log->payment_cheak_status = 1;
                $log->save();

            }
        }

//        if ($request->payment_type == 2) {
//
//            //$image = 'img/no_image.png';
//
////            if ($request->cheque_image) {
////                // Upload Image
////                $file = $request->file('cheque_image');
////                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
////                $destinationPath = 'public/uploads/sales_payment_cheque';
////                $file->move($destinationPath, $filename);
////                $image = 'uploads/sales_payment_cheque/'.$filename;
////            }
//
//            $payment = new SalePayment();
//            $payment->sales_order_id = $order->id;
//            $payment->customer_id = $request->customer;
//            $payment->company_branch_id = $order->company_branch_id;
//            $payment->transaction_method = 2;
//            $payment->client_bank_name = $request->client_bank_name;
//            $payment->client_cheque_no = $request->client_cheque_no;
//            $payment->client_amount = $request->client_amount;
////                $payment->bank_id = $request->bank;
////                $payment->branch_id = $request->branch;
////                $payment->bank_account_id = $request->account;
////                $payment->cheque_no = $request->cheque_no;
////                $payment->cheque_image = $image;
//            $payment->amount = $request->client_amount;
//            $payment->date = $request->date;
//            $payment->cheque_date = $request->cheque_date;
//            $payment->note = $request->note;
//            $payment->status = 1;
//            $payment->save();
//
//            //BankAccount::find($request->account)->decrement('balance', $request->paid);
//
//            $log = new TransactionLog();
//            $log->date = $request->date;
//            $log->particular = 'Rev. from '.($order->order_no).' '.$order->customer->name;
//            $log->transaction_type = 1;
//            $log->transaction_method = 2;
//            $log->account_head_type_id = 2;
//            $log->account_head_sub_type_id = 2;
//            //$log->bank_id = $request->bank;
//            //$log->branch_id = $request->branch;
//            //$log->bank_account_id = $request->account;
//            //$log->cheque_no = $request->cheque_no;
//            //$log->cheque_image = $image;
//            $log->amount = $request->client_amount;
//            $log->customer_id = $request->customer ?? null;
//            $log->company_branch_id = $order->company_branch_id;
//            $log->sales_order_id = $order->id;
//            $log->sale_payment_id = $payment->id;
//            $log->payment_cheak_status = 1;
//            $log->save();
//
//        }
        return redirect()->route('sale_receipt.details', ['order' => $order->id,'receipt'=>1,'type'=>'retail_sale']);
    }

    public function wholeSaleOrderPost(Request $request){
        //return($request->all());
        if (empty($request->product_serial)) {
            $message = 'No Product Item Found';
            return redirect()->back()->withInput()->with('message', $message);
        }

        $total = $request->total;
        $due = $request->due_total;
        $rules = [
            'date' => 'required|date',
            'customer' => 'required|numeric',
        ];

        if (Auth::user()->company_branch_id == 0) {
            $rules['companyBranch'] = 'required';
        }
        if ($request->product_serial) {
            $rules['paid'] = 'required|numeric|min:0';
            $rules['invoice_type'] = 'required';
            $rules['product_serial.*'] = 'required';
            $rules['product_item.*'] = 'required';
            $rules['product_category.*'] = 'required';
            $rules['product_color.*'] = 'required';
            $rules['product_size.*'] = 'required';
            $rules['warehouse.*'] = 'required';
            $rules['quantity.*'] = 'required|numeric|min:.01';
            $rules['unit_price.*'] = 'required|numeric|min:0';
        }


        if ($due > 0)
            $rules['next_payment'] = 'nullable|date';

        if ($request->payment_type == '3') {
            $rules['client_bank_name'] = 'required';
            $rules['client_cheque_no'] = 'required';
            $rules['cheque_date'] = 'required';
            $rules['cheque_amount'] = 'required';
        }
        if ($request->payment_type == '2') {
            $rules['account_no'] = 'required';
        }

        $request->validate($rules);

        $returnOrder=ProductReturnOrder::where('customer_id',$request->customer)->where('status',0)->first();
        if($returnOrder){
            $returnProfit = $returnOrder->profit;
            $returnOrder->status=1;
            $returnOrder->save();
        }else{
            $returnProfit = 0;
        }

        $available = true;
        $message = '';

         if ($request->purchase_inventory) {
                foreach ($request->purchase_inventory as $index => $purchase_inventory_id) {
                    $inventory = PurchaseInventory::find($purchase_inventory_id);
                    if ($inventory) {
                        if ($request->quantity[$index] > $inventory->quantity) {
                            $available = false;
                            $message = 'Insufficient ' . $inventory->serial;
                            break;
                        }
                    }
                }
            }
         if ($request->payment_type == 3 && $request->paid > 0) {
             $available = false;
             $message = 'Paid must be zero(0)';
            }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }



        $order = new SalesOrder();
//        if (Auth::user()->company_branch_id == 0) {
//            $order->company_branch_id = $request->companyBranch;
//        }else{
//            $order->company_branch_id = Auth::user()->company_branch_id;
//        }
        $order->company_branch_id = 1;

        $order->type = $request->type;
        $order->invoice_type = $request->invoice_type;
        $order->customer_id = $request->customer;
//        $order->received_by = $request->received_by;
        $order->date = $request->date;
        $order->note = $request->note;
        $order->sub_total = 0;
        $order->vat_percentage = $request->vat;
        $order->vat = 0;
        $order->discount_percentage = $request->discount_percentage;
        $order->discount = $request->discount;
        $order->transport_cost = $request->transport_cost;
        $order->return_amount = $request->return_amount??0;
        $order->sale_adjustment = $request->sale_adjustment??0;
        $order->sale_type = $request->sale_type;
        $order->total = 0;
        $order->paid = $request->paid;
        if ($request->payment_type == 3){
            $order->client_bank_name = $request->client_bank_name;
            $order->client_cheque_no = $request->client_cheque_no;
            $order->cheque_date = $request->cheque_date;
            $order->client_amount = $request->cheque_amount;
        }
        $order->due = 0;
        $order->previous_due = $request->previous_due;
        $order->current_due = $request->due_total;
        $order->user_id = Auth::user()->id;

        $branch_id = 1;
        $checkSaleOrder = SalesOrder::where('type',2)->where('company_branch_id',$branch_id)->orderBy('id','desc')->first();
        if ($checkSaleOrder){
            $order_no = (int)$checkSaleOrder->order_no + 1;
        }else{
            $order_no = 1;
        }
        $order->order_no = str_pad($order_no, 6, 0, STR_PAD_LEFT);
        $order->save();

        $subTotal = 0;
        $invoiceProfit = 0;
        if  ($request->purchase_inventory) {
            foreach ($request->purchase_inventory as $key => $purchase_inventory_id) {
                $inventory = PurchaseInventory::find($purchase_inventory_id);
                if ($inventory) {
                    $sales_order_product = SalesOrderProduct::where('sales_order_id', $order->id)->where('purchase_inventory_id', $purchase_inventory_id)->first();
                    if (empty($sales_order_product)) {
                        SalesOrderProduct::create([
                            'sales_order_id' => $order->id,
                            'purchase_inventory_id' => $purchase_inventory_id,
                            'product_item_id' => $inventory->product_item_id,
                            'product_category_id' => $inventory->product_category_id,
                            'warehouse_id' => $inventory->warehouse_id,
                            'serial' => $inventory->serial,
                            'quantity' => $request->quantity[$key],
                            'buy_price' => $inventory->unit_price,
                            'unit_price' => $request->unit_price[$key],
                            'total' => $request->quantity[$key] * $request->unit_price[$key],
                        ]);

                            // Inventory Log
                            $inventoryLog = PurchaseInventoryLog::where('sales_order_id', $order->id)
                                ->where('purchase_inventory_id', $purchase_inventory_id)
                                ->where('type', 2)->first();
                            if (empty($inventoryLog)) {
                                $inventoryLog = new PurchaseInventoryLog();
                            }

                            $inventoryLog->product_item_id = $inventory->product_item_id;
                            $inventoryLog->product_category_id = $inventory->product_category_id;
                            $inventoryLog->warehouse_id = $inventory->warehouse_id;
                            $inventoryLog->serial = $inventory->serial;
                            $inventoryLog->type = 2;
                            $inventoryLog->date = $request->date;
                            $inventoryLog->quantity = $request->quantity[$key];
                            $inventoryLog->unit_price = $inventory->unit_price;
                            $inventoryLog->selling_price = $request->unit_price[$key];
                            $inventoryLog->wholesale_price = $inventory->wholesale_price;
                            $inventoryLog->sale_total = $request->quantity[$key] * $request->unit_price[$key];
                            $inventoryLog->total = $request->quantity[$key] * $inventory->unit_price;
                            $inventoryLog->sales_order_id = $order->id;
                            $inventoryLog->purchase_inventory_id = $inventory->id;
                            $inventoryLog->note = 'Sale Product';
                            $inventoryLog->user_id = Auth::id();
                            $inventoryLog->save();

                        $subTotal += $request->quantity[$key] * $request->unit_price[$key];
                        $invoiceProfit += ($request->quantity[$key] * $request->unit_price[$key])-($inventory->avg_unit_price*$request->quantity[$key]);

                            $inventory->decrement('quantity', $request->quantity[$key]);
                        // $inventory->update([
                        //     'quantity' => $inventory->in_product - $inventory->out_product,
                        // ]);
                    }

                }

            }
        }

        $order->sub_total = $subTotal;
        $vat = ($subTotal * $request->vat) / 100;
        $order->vat = $vat;
        $total = $subTotal + $request->transport_cost + $vat - $request->discount - $request->return_amount - $request->sale_adjustment;
        $order->total = $total;
        $order->invoice_total = $total > $request->paid ? $request->paid : $total;
        if ($request->paid > $total){
            $due = 0;
        }else{
            $due = $total - $request->paid;
        }
        $order->due = $due;
        $order->invoice_profit = round($invoiceProfit-$request->discount-$returnProfit);
        $order->next_payment = $due > 0 ? $request->next_payment??'' : null;
        $order->save();

        //Net profit log
        $log = new TransactionLog();
        $log->date = $request->date;
        $log->particular = 'Receive from '.($order->order_no).' '.$order->customer->name;
        $log->transaction_type = 1;
        $log->transaction_method = 1;
        $log->account_head_type_id = 2;
        $log->account_head_sub_type_id = 2;
        $log->amount =  round($invoiceProfit-$request->discount-$returnProfit);
        $log->company_branch_id = $order->company_branch_id;
        $log->sale_type_status = 2;
        $log->net_profit = 1;
        $log->save();

        //Return paid log

        if($request->return_amount > 0){
            $returnPaid = new ReturnPaidLog();
            $returnPaid->customer_id = $request->customer;
            $returnPaid->sales_order_id = $order->id;
            $returnPaid->product_return_order_id = $returnOrder->id;
            $returnPaid->amount = $request->return_amount;
            $returnPaid->date = $request->date;
            $returnPaid->save();
        }

        // Sales Payment
        if ($request->paid > 0) {
            if ($request->payment_type == 1){
                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->customer_id = $request->customer;
                $payment->sale_type_status = 2;
                $payment->company_branch_id = $order->company_branch_id;
                $payment->transaction_method = 1;
                $payment->received_type = 1;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->status = 2;
                $payment->save();

                Cash::first()->increment('amount', $request->paid);
                //BranchCash::where('company_branch_id',$order->company_branch_id)->first()->increment('amount', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Rev. from '.($order->order_no).' '.$order->customer->name;
                $log->transaction_type = 1;
                $log->transaction_method = 1;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->amount =  $request->paid;
                $log->customer_id = $request->customer??null;
                $log->sale_type_status = 2;
                $log->sale_payment_id = $payment->id;
                $log->company_branch_id = $order->company_branch_id;
                $log->sales_order_id = $order->id;
                $log->save();
            }
            if ($request->payment_type == 2) {

                $bankAccount = BankAccount::find($request->account_no);

                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->customer_id = $request->customer;
                $payment->sale_type_status = 2;
                $payment->company_branch_id = $order->company_branch_id;
                $payment->transaction_method= 2;
//                $payment->client_bank_name = $request->client_bank_name;
//                $payment->client_cheque_no = $request->client_cheque_no;
//                $payment->client_amount = $request->paid;
                $payment->bank_id = $bankAccount->bank_id;
                $payment->branch_id = $bankAccount->branch_id;
                $payment->bank_account_id = $bankAccount->id;
                $payment->cheque_no = $request->cheque_no;
//                $payment->cheque_image = $image;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->cheque_date = $request->cheque_date;
                $payment->note = $request->note;
                $payment->status = 2;
                $payment->save();

                BankAccount::find($request->account_no)->increment('balance', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Rev. from '.($order->order_no).' '.$order->customer->name;
                $log->transaction_type = 1;
                $log->transaction_method = 2;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->bank_id = $bankAccount->bank_id;
                $log->branch_id = $bankAccount->branch_id;
                $log->bank_account_id = $bankAccount->id;
//                $log->cheque_no = $request->cheque_no;
//                $log->cheque_image = $image;
                $log->amount = $request->paid;
                $log->customer_id = $request->customer ?? null;
                $log->sale_type_status = 2;
                $log->company_branch_id = $order->company_branch_id;
                $log->sales_order_id = $order->id;
                $log->sale_payment_id = $payment->id;
                $log->payment_cheak_status = 1;
                $log->save();

            }
        }

//        if ($request->payment_type == 2) {
//
//            //$image = 'img/no_image.png';
//
////            if ($request->cheque_image) {
////                // Upload Image
////                $file = $request->file('cheque_image');
////                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
////                $destinationPath = 'public/uploads/sales_payment_cheque';
////                $file->move($destinationPath, $filename);
////                $image = 'uploads/sales_payment_cheque/'.$filename;
////            }
//
//            $payment = new SalePayment();
//            $payment->sales_order_id = $order->id;
//            $payment->customer_id = $request->customer;
//            $payment->company_branch_id = $order->company_branch_id;
//            $payment->transaction_method = 2;
//            $payment->client_bank_name = $request->client_bank_name;
//            $payment->client_cheque_no = $request->client_cheque_no;
//            $payment->client_amount = $request->client_amount;
////                $payment->bank_id = $request->bank;
////                $payment->branch_id = $request->branch;
////                $payment->bank_account_id = $request->account;
////                $payment->cheque_no = $request->cheque_no;
////                $payment->cheque_image = $image;
//            $payment->amount = $request->client_amount;
//            $payment->date = $request->date;
//            $payment->cheque_date = $request->cheque_date;
//            $payment->note = $request->note;
//            $payment->status = 1;
//            $payment->save();
//
//            //BankAccount::find($request->account)->decrement('balance', $request->paid);
//
//            $log = new TransactionLog();
//            $log->date = $request->date;
//            $log->particular = 'Rev. from '.($order->order_no).' '.$order->customer->name;
//            $log->transaction_type = 1;
//            $log->transaction_method = 2;
//            $log->account_head_type_id = 2;
//            $log->account_head_sub_type_id = 2;
//            //$log->bank_id = $request->bank;
//            //$log->branch_id = $request->branch;
//            //$log->bank_account_id = $request->account;
//            //$log->cheque_no = $request->cheque_no;
//            //$log->cheque_image = $image;
//            $log->amount = $request->client_amount;
//            $log->customer_id = $request->customer ?? null;
//            $log->company_branch_id = $order->company_branch_id;
//            $log->sales_order_id = $order->id;
//            $log->sale_payment_id = $payment->id;
//            $log->payment_cheak_status = 1;
//            $log->save();
//
//        }
        return redirect()->route('sale_receipt.details', ['order' => $order->id,'receipt'=>1,'type'=>'whole_sale']);
    }

    public function salesWastagePost(Request $request)
    {
//        dd($request->all());
        if (empty($request->product_serial)) {
            $message = 'No Product Item Found';
            return redirect()->back()->withInput()->with('message', $message);
        }

        $total = $request->total;
        $due = $request->due_total;
        $rules = [
            'date' => 'required|date',
        ];

        if (Auth::user()->company_branch_id == 0) {
            $rules = [
                'companyBranch' => 'required',
            ];
        }
        if ($request->product_serial) {
//            $rules['invoice_type'] = 'required';
            $rules['product_serial.*'] = 'required';
            $rules['product_item.*'] = 'required';
            $rules['product_category.*'] = 'required';
            $rules['product_color.*'] = 'required';
            $rules['product_size.*'] = 'required';
            $rules['warehouse.*'] = 'required';
            $rules['quantity.*'] = 'required|numeric|min:.01';
            $rules['unit_price.*'] = 'required|numeric|min:0';
        }

        if ($request->customer_type == 1) {
            $rules['customer_name'] = 'required';
            $rules['mobile_no'] = 'required';
            $rules['address'] = 'required';
        }
        if ($request->customer_type == 2) {
            $rules['customer'] = 'required';
        }

        if ($due > 0)
            $rules['next_payment'] = 'required|date';

        if ($request->payment_type == '2') {
            $rules['client_bank_name'] = 'required';
            $rules['client_cheque_no'] = 'required';
            $rules['cheque_date'] = 'required';
            $rules['client_amount'] = 'required';
        }

        $request->validate($rules);

        if ($request->customer_type == 1) {
            $customer = new Customer();
            $customer->name = $request->customer_name;
            if (Auth::user()->company_branch_id == 0) {
                $customer->company_branch_id = $request->companyBranch;
            } else {
                $customer->company_branch_id = Auth::user()->company_branch_id;
            }
            $customer->mobile_no = $request->mobile_no;
            $customer->address = $request->address;
            $customer->save();
        } else {
            $customer = Customer::find($request->customer);
        }

        $available = true;
        $message = '';

         if ($request->purchase_inventory) {
                foreach ($request->purchase_inventory as $index => $purchase_inventory_id) {
                    $inventory = PurchaseInventory::find($purchase_inventory_id);
                    if ($inventory) {
                        if ($request->quantity[$index] > $inventory->quantity) {
                            $available = false;
                            $message = 'Insufficient ' . $inventory->serial;
                            break;
                        }
                    }
                }
            }


        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $order = new SalesOrder();
        if (Auth::user()->company_branch_id == 0) {
            $order->company_branch_id = $request->companyBranch;
        }else{
            $order->company_branch_id = Auth::user()->company_branch_id;
        }

        $order->invoice_type = $request->invoice_type;
        $order->customer_id = $customer->id;
        $order->received_by = $request->received_by;
        $order->date = $request->date;
        $order->note = $request->note;
        $order->sub_total = 0;
        $order->vat_percentage = $request->vat;
        $order->vat = 0;
        $order->discount_percentage = $request->discount_percentage;
        $order->discount = $request->discount;
        $order->transport_cost = $request->transport_cost;
        $order->return_amount = $request->return_amount;
        $order->sale_type = $request->sale_type;
        $order->total = 0;
        $order->paid = $request->paid;
        if ($request->payment_type == 2){
            $order->client_bank_name = $request->client_bank_name;
            $order->client_cheque_no = $request->client_cheque_no;
            $order->cheque_date = $request->cheque_date;
            $order->client_amount = $request->client_amount;
        }
        $order->due = 0;
        $order->previous_due = $request->previous_due;
        $order->current_due = $request->due_total;
        $order->user_id = Auth::user()->id;
        $order->save();
        $order->order_no = str_pad($order->id, 6, 0, STR_PAD_LEFT);
        $order->save();

        $subTotal = 0;
        if  ($request->purchase_inventory) {
            foreach ($request->purchase_inventory as $key => $purchase_inventory_id) {
                $inventory = PurchaseInventory::find($purchase_inventory_id);
                if ($inventory) {
                    $sales_order_product = SalesOrderProduct::where('sales_order_id', $order->id)->where('purchase_inventory_id', $purchase_inventory_id)->first();
                    if (empty($sales_order_product)) {
                        SalesOrderProduct::create([
                            'sales_order_id' => $order->id,
                            'purchase_inventory_id' => $purchase_inventory_id,
                            'product_item_id' => $inventory->product_item_id,
                            'product_category_id' => $inventory->product_category_id,
                            'warehouse_id' => $inventory->warehouse_id,
                            'serial' => $inventory->serial,
                            'quantity' => $request->quantity[$key],
                            'buy_price' => $inventory->unit_price,
                            'unit_price' => $request->unit_price[$key],
                            'total' => $request->quantity[$key] * $request->unit_price[$key],
                        ]);

                        $subTotal += $request->quantity[$key] * $request->unit_price[$key];

                            // Inventory Log
                            $inventoryLog = PurchaseInventoryLog::where('sales_order_id', $order->id)
                                ->where('purchase_inventory_id', $purchase_inventory_id)
                                ->where('type', 2)->first();

                            if (empty($inventoryLog)) {
                                $inventoryLog = new PurchaseInventoryLog();
                            }

                            $inventoryLog->product_item_id = $inventory->product_item_id;
                            $inventoryLog->product_category_id = $inventory->product_category_id;
                            $inventoryLog->warehouse_id = $inventory->warehouse_id;
                            $inventoryLog->serial = $inventory->serial;
                            $inventoryLog->type = 2;
                            $inventoryLog->date = $request->date;
                            $inventoryLog->quantity = $request->quantity[$key];
                            $inventoryLog->unit_price = $inventory->unit_price;
                            $inventoryLog->selling_price = $request->unit_price[$key];
                            $inventoryLog->sale_total = $request->quantity[$key] * $inventoryLog->selling_price;
                            $inventoryLog->total = $request->quantity[$key] * $inventoryLog->unit_price;
                            $inventoryLog->sales_order_id = $order->id;
                            $inventoryLog->purchase_inventory_id = $inventory->id;
                            $inventoryLog->note = 'Sale Product';
                            $inventoryLog->stock_type = 15;
                            $inventoryLog->user_id = Auth::id();
                            $inventoryLog->save();

                            $inventory->decrement('quantity', $request->quantity[$key]);
                        // $inventory->update([
                        //     'quantity' => $inventory->in_product - $inventory->out_product,
                        // ]);
                    }

                }

            }
        }

        $order->sub_total = $subTotal;
        $vat = ($subTotal * $request->vat) / 100;
        $order->vat = $vat;
        $total = $subTotal + $request->transport_cost + $vat - $request->discount - $request->return_amount;
        $order->total = $total;
        $due = $total - $request->paid;
        $order->due = $due;
        $order->next_payment = $due > 0 ? $request->next_payment : null;
        $order->save();

        // Sales Payment
        if ($request->paid > 0) {
            if ($request->payment_type == 1) {
                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->customer_id = $customer->id;
                $payment->company_branch_id = $order->company_branch_id;
                $payment->transaction_method = 1;
                $payment->received_type = 1;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->status = 2;
                $payment->save();

                Cash::first()->increment('amount', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Rev. from ' . ($order->order_no) . ' ' . $order->customer->name;
                $log->transaction_type = 1;
                $log->transaction_method = 1;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->amount = $request->paid;
                $log->customer_id = $request->customer ?? null;
                $log->sale_payment_id = $payment->id;
                $log->company_branch_id = $order->company_branch_id;
                $log->sales_order_id = $order->id;
                $log->save();
            }else {

                $image = 'img/no_image.png';
                if ($request->cheque_image) {
                    // Upload Image
                    $file = $request->file('cheque_image');
                    $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                    $destinationPath = 'public/uploads/sales_payment_cheque';
                    $file->move($destinationPath, $filename);
                    $image = 'uploads/sales_payment_cheque/'.$filename;
                }

                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->customer_id = $customer->id;
                $payment->company_branch_id = $order->company_branch_id;
                $payment->transaction_method = 2;
                $payment->received_type = 1;
                $payment->client_bank_name = $request->client_bank_name;
                $payment->client_cheque_no = $request->client_cheque_no;
                $payment->client_amount = $request->client_amount;
                $payment->bank_id = $request->bank;
                $payment->branch_id = $request->branch;
                $payment->bank_account_id = $request->account;
                $payment->cheque_no = $request->cheque_no;
                $payment->cheque_image = $image;
                $payment->amount = $request->client_amount;
                $payment->date = $request->date;
                $payment->cheque_date = $request->cheque_date;
                $payment->note = $request->note;
                $payment->status = 1;
                $payment->save();

                BankAccount::find($request->account)->incriment('balance', $request->client_amount);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Rev. from '.($order->order_no).' '.$order->customer->name;
                $log->transaction_type = 1;
                $log->transaction_method = 2;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->bank_id = $request->bank;
                $log->branch_id = $request->branch;
                $log->bank_account_id = $request->account;
                $log->cheque_no = $request->cheque_no;
                $log->cheque_image = $image;
                $log->amount = $request->client_amount;
                $log->customer_id = $customer->id ?? null;
                $log->company_branch_id = $order->company_branch_id;
                $log->sales_order_id = $order->id;
                $log->sale_payment_id = $payment->id;
                $log->payment_cheak_status = 1;
                $log->save();
            }
        }

        return redirect()->route('sale_receipt.details', ['order' => $order->id]);
    }

    public function manuallyChequeIn() {
         if (Auth::user()->company_branch_id == 0) {
            $customers = Customer::where('status', 1)->orderBy('name')->get();
        }else{
            $customers = Customer::where('status', 1)->where('company_branch_id',Auth::user()->company_branch_id)->orderBy('name')->get();
        }
        return view('sale.client_payment.manually_chequeIn',compact('customers'));
    }
    public function manuallyChequeInPost(Request $request) {

        $request->validate([
            'customer' => 'required',
            'sale_order_no' => 'nullable',
            'client_bank_name' => 'required|string|max:255',
            'client_cheque_no' => 'required|max:255',
            'cheque_date' => 'required',
            'client_amount' => 'required|min:0',
        ]);

        $customer = Customer::find($request->customer);

            $payment = new SalePayment();
            $payment->sales_order_id = $request->sale_order_no??null;
            $payment->customer_id = $customer->id;
            $payment->company_branch_id = 1;
            $payment->transaction_method = 2;
            //$payment->client_bank_name = $request->client_bank_name;
            $payment->client_cheque_no = $request->client_cheque_no;
            $payment->cheque_date = $request->cheque_date;
            $payment->client_amount = $request->client_amount;
            $payment->amount = $request->client_amount;
            $payment->date = Carbon::now();
            $payment->cheque_date = $request->cheque_date;
            $payment->note = $request->note;
            $payment->status = 1;
            $payment->save();

            $log = new TransactionLog();
            $log->date = Carbon::now();
            $log->particular = 'Rev. from '."Manual Cheque".' '.$customer->name;
            $log->transaction_type = 1;
            $log->transaction_method = 2;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->amount = $request->client_amount;
            $log->customer_id = $customer->id ?? null;
            $log->company_branch_id = 1;
            $log->sales_order_id = $request->sale_order_no??null;
            $log->sale_payment_id = $payment->id;
            $log->payment_cheak_status = 1;
            $log->save();

        return redirect()->route('client_payment.all_pending_check')->with('message','Cheque Add Successfully');
    }

    public function saleReceiptCustomer() {
        return view('sale.receipt.customer_all');
    }
    public function saleReceiptViewTrash(){
        $receipts = SalesOrder::onlyTrashed()->paginate(10);
        return view('sale.receipt.sale_receipt_view_trash',compact('receipts'));
    }
    public function saleReceiptCustomerWarehousePending() {
        return view('sale.receipt.customer_all_warehouse_pending');
    }

    public function saleWastageReceiptCustomer() {
        return view('sale.receipt.wastage_customer_all');
    }

    public function saleReceiptSupplier() {
        return view('sale.receipt.supplier_all');
    }

    public function saleReceiptDetails(SalesOrder $order) {
        return view('sale.receipt.details', compact('order'));
    }
    public function saleReceiptPreview(SalesOrder $order){
        if($order->total > 0){
            $order->amount_in_word = DecimalToWords::convert($order->total,'Taka', 'Poisa');
        }
        $payment = SalePayment::where('sales_order_id', $order->id)->get();
        $salesOrderProducts = SalesOrderProduct::where('sales_order_id',$order->id)->get();
        return view('sale.receipt.receipt_preview', compact('order','salesOrderProducts','payment'));
    }
    public function saleReceiptPrint(SalesOrder $order) {
        if($order->total > 0){
            $order->amount_in_word = DecimalToWords::convert($order->total,'Taka', 'Poisa');
        }
        $payment = SalePayment::where('sales_order_id', $order->id)->get();
        $salesOrderProducts = SalesOrderProduct::where('sales_order_id',$order->id)->get();
        //NumberToBangla()
        return view('sale.receipt.print_receipt2', compact('order','salesOrderProducts','payment'));
    }

    public function requisitionPrint(SalesOrder $order) {
        if($order->total > 0){
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
        }

        return view('sale.receipt.requisition_print', compact('order'));
    }
    public function SuperRequisitionPrint(SalesOrder $order) {
    if($order->total > 0){
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
    }
        return view('sale.receipt.super_market_requisition_print', compact('order'));
    }

    public function saleReceiptWpadPrint(SalesOrder $order) {
        if($order->total > 0){
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
        }


        return view('sale.receipt.wpad_print', compact('order'));
    }

    public function saleReceiptChallanPreview(SalesOrder $order) {
        if($order->total > 0){
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
        }

        return view('sale.receipt.challan_preview', compact('order'));
    }

    public function saleReceiptChallanPrint(SalesOrder $order) {
        if($order->total > 0){
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
        }

        return view('sale.receipt.challan', compact('order'));
    }

    public function saleReceiptChallanWpadPrint(SalesOrder $order) {
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');

        return view('sale.receipt.wpad_challan', compact('order'));
    }

    public function salePaymentDetails(SalePayment $payment) {
        if($payment->amount > 0){
        $payment->amount_in_word = DecimalToWords::convert(($payment->amount ),'Taka',
            'Poisa');
        }
        return view('sale.receipt.payment_details', compact('payment'));
    }

    public function salePaymentPrint(SalePayment $payment) {
        if($payment->amount > 0){
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        }
        return view('sale.receipt.payment_print', compact('payment'));
    }

    public function clientPaymentCustomer() {
        if(\request()->get('type') == 'retail_sale'){
            $type = 1;
        }elseif (\request()->get('type') == 'whole_sale'){
            $type = 2;
        }
        $customers = Customer::where('type',$type)->get();
        $transactions = TransactionLog::where('sale_payment_id','!=',null)->where('sales_order_id','=',null)->where('company_branch_id',1)->get();
        //dd($customers->sum('paid'));
        //dd($transactions);
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        return view('sale.client_payment.customer_all', compact('banks','customers'));
    }

    public function clientPaymentSupplier() {
        $banks = Bank::where('status', 1)->orderBy('name')->get();

        return view('sale.client_payment.supplier_all', compact('banks'));
    }

    public function clientPaymentGetOrders(Request $request) {
        $orders = SalesOrder::where('customer_id', $request->clientId)
            ->where('due', '>', 0)
            ->orderBy('id', 'desc')
            ->get()->toArray();

        return response()->json($orders);
    }

    public function clientPaymentGetOrdersSupplier(Request $request) {
        $orders = SalesOrder::where('supplier_id', $request->clientId)
            ->where('due', '>', 0)
            ->orderBy('id', 'desc')
            ->get()->toArray();

        return response()->json($orders);
    }

 public function makePayment(Request $request) {
        //dd($request->all());
        $rules = [
            'customer_id' => 'required',
            'payment_type' => 'required',
            'amount' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'payment_discount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ];
         if ($request->payment_type == '1' || $request->payment_type == '3') {
             $rules['amount'] = 'required|numeric|min:1';
         }
        if ($request->payment_type == '2') {
            //$rules['bank'] = 'required';
            //$rules['branch'] = 'required';
            $rules['cheque_no'] = 'required|string|max:255';
            $rules['cheque_amount'] = 'required';
            $rules['cheque_date'] = 'required';
        }

         if ($request->order != '') {
             $order = SalesOrder::find($request->order);
             $rules['amount'] = 'required|numeric|min:0|max:'.$order->due;
         }

         if ($request->customer_id != '') {
             $customer = Customer::find($request->customer_id);
             if ($request->payment_type == '1'){
                 $rules['amount'] = 'required|numeric|min:0|max:'.$customer->due;
             }elseif ($request->payment_type == '2'){
                 $rules['cheque_amount'] = 'required|numeric|min:0|max:'.$customer->due;
             }else{
                 if ($request->amount+$request->cheque_amount>$customer->due){
                     return response()->json(['success' => false, 'message' => 'Cash & Cheque amount must  be less than or equal to Customer due amount!']);
                 }
             }
         }

//         if ($request->order != '') {
//             if ($request->amount < $order->due)
//                 $rules['next_payment_date'] = 'required|date';
//         }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        if ($request->type == 'retail_sale'){
            $customer_type = 1;
        }elseif ($request->type == 'whole_sale'){
            $customer_type = 2;
        }
        $totalAmount = $request->amount + $request->payment_discount;
        $customer = Customer::find($request->customer_id);

         if ($request->type == 'retail_sale'){
             $payment = new SalePayment();
             $payment->sales_order_id = null;
             $payment->customer_id = $request->customer_id;
             $payment->sale_type_status = $customer_type;
             $payment->company_branch_id = $request->branch_id;
             $payment->transaction_method = $request->payment_type;
             $payment->amount = $request->amount;
             $payment->remaining_due = $customer->due - $totalAmount;
             $payment->payment_discount = $request->payment_discount;
             $payment->date = $request->date;
             $payment->note = $request->note;
             $payment->status = 2;
             $payment->save();

             //Balance Increment
             Cash::first()->increment('amount', $request->amount);
             //BranchCash::where('company_branch_id',$customer->company_branch_id)->first()->increment('amount', $request->amount);

             $log = new TransactionLog();
             $log->date = $request->date;
             $log->particular = 'Payment from ' . $customer->name;
             $log->transaction_type = 1;
             $log->transaction_method = $request->payment_type;
             $log->account_head_type_id = 2;
             $log->account_head_sub_type_id = 2;
             $log->amount = $request->amount;
             $log->note = $request->note;
             $log->customer_id = $request->customer_id;
             $log->sale_type_status = $customer_type;
             $log->sale_payment_id = $payment->id;
             $log->company_branch_id = $customer->company_branch_id;
             $log->save();
         }elseif ($request->type == 'whole_sale'){
             if ($request->payment_type == 1) {
                 $payment = new SalePayment();
                 $payment->sales_order_id = null;
                 $payment->customer_id = $request->customer_id;
                 $payment->sale_type_status = $customer_type;
                 $payment->company_branch_id = $request->branch_id;
                 $payment->transaction_method = $request->payment_type;
                 $payment->amount = $request->amount;
                 $payment->remaining_due = $customer->due - $totalAmount;
                 $payment->payment_discount = $request->payment_discount;
                 $payment->date = $request->date;
                 $payment->note = $request->note;
                 $payment->status = 1;
                 $payment->save();

                 //Balance Increment
                 //Cash::first()->increment('amount', $request->amount);
                 //BranchCash::where('company_branch_id',$customer->company_branch_id)->first()->increment('amount', $request->amount);

                 $log = new TransactionLog();
                 $log->date = $request->date;
                 $log->particular = 'Payment from ' . $customer->name;
                 $log->transaction_type = 1;
                 $log->transaction_method = $request->payment_type;
                 $log->account_head_type_id = 2;
                 $log->account_head_sub_type_id = 2;
                 $log->amount = $request->amount;
                 $log->note = $request->note;
                 $log->customer_id = $request->customer_id;
                 $log->sale_type_status = $customer_type;
                 $log->sale_payment_id = $payment->id;
                 $log->company_branch_id = $customer->company_branch_id;
                 $log->payment_cheak_status = 1;
                 $log->save();
             }elseif ($request->payment_type == 2) {
                 $payment = new SalePayment();
                 $payment->sales_order_id = null;
                 $payment->customer_id = $customer->id;
                 $payment->sale_type_status = $customer_type;
                 $payment->company_branch_id = $request->branch_id;
                 $payment->transaction_method = 2;
                 //$payment->client_bank_name = $request->client_bank_name;
                 $payment->client_cheque_no = $request->cheque_no;
                 $payment->cheque_date = $request->cheque_date;
                 $payment->client_amount = $request->cheque_amount;
                 $payment->amount = $request->cheque_amount;
                 $payment->date = $request->cheque_date;
                 $payment->cheque_date = $request->cheque_date;
                 $payment->note = $request->note;
                 $payment->status = 1;
                 $payment->save();

                 $log = new TransactionLog();
                 $log->date = $request->cheque_date;
                 $log->particular = 'Rev. from '."Cheque".' '.$customer->name;
                 $log->transaction_type = 1;
                 $log->transaction_method = 2;
                 $log->account_head_type_id = 2;
                 $log->account_head_sub_type_id = 2;
                 $log->amount = $request->cheque_amount;
                 $log->customer_id = $customer->id;
                 $log->sale_type_status = $customer_type;
                 $log->company_branch_id = $customer->company_branch_id;
                 $log->sales_order_id = null;
                 $log->sale_payment_id = $payment->id;
                 $log->payment_cheak_status = 1;
                 $log->save();
             }else{
                 //Cash Payment
                 $payment = new SalePayment();
                 $payment->sales_order_id = null;
                 $payment->customer_id = $request->customer_id;
                 $payment->sale_type_status = $customer_type;
                 $payment->company_branch_id = $request->branch_id;
                 $payment->transaction_method = $request->payment_type;
                 $payment->amount = $request->amount;
                 $payment->remaining_due = $customer->due - $totalAmount;
                 $payment->payment_discount = $request->payment_discount;
                 $payment->date = $request->date;
                 $payment->note = $request->note;
                 $payment->status = 1;
                 $payment->save();

                 //Balance Increment
                 //Cash::first()->increment('amount', $request->amount);
                 //BranchCash::where('company_branch_id',$customer->company_branch_id)->first()->increment('amount', $request->amount);

                 $log = new TransactionLog();
                 $log->date = $request->date;
                 $log->particular = 'Payment from ' . $customer->name;
                 $log->transaction_type = 1;
                 $log->transaction_method = $request->payment_type;
                 $log->account_head_type_id = 2;
                 $log->account_head_sub_type_id = 2;
                 $log->amount = $request->amount;
                 $log->note = $request->note;
                 $log->customer_id = $request->customer_id;
                 $log->sale_type_status = $customer_type;
                 $log->sale_payment_id = $payment->id;
                 $log->company_branch_id = $customer->company_branch_id;
                 $log->payment_cheak_status = 1;
                 $log->save();

                 //Cheque Payment
                 $payment = new SalePayment();
                 $payment->sales_order_id = null;
                 $payment->customer_id = $customer->id;
                 $payment->sale_type_status = $customer_type;
                 $payment->company_branch_id = 1;
                 $payment->transaction_method = 2;
                 //$payment->client_bank_name = $request->client_bank_name;
                 $payment->client_cheque_no = $request->cheque_no;
                 $payment->cheque_date = $request->cheque_date;
                 $payment->client_amount = $request->cheque_amount;
                 $payment->amount = $request->cheque_amount;
                 $payment->date = $request->cheque_date;
                 $payment->cheque_date = $request->cheque_date;
                 $payment->note = $request->note;
                 $payment->status = 1;
                 $payment->save();

                 $log = new TransactionLog();
                 $log->date = $request->cheque_date;
                 $log->particular = 'Rev. from '."Cheque".' '.$customer->name;
                 $log->transaction_type = 1;
                 $log->transaction_method = 2;
                 $log->account_head_type_id = 2;
                 $log->account_head_sub_type_id = 2;
                 $log->amount = $request->cheque_amount;
                 $log->customer_id = $customer->id;
                 $log->sale_type_status = $customer_type;
                 $log->company_branch_id = $customer->company_branch_id;;
                 $log->sales_order_id = null;
                 $log->sale_payment_id = $payment->id;
                 $log->payment_cheak_status = 1;
                 $log->save();
             }
         }

        if($request->payment_discount > 0){
            $partyLess = new PartyLess();
            $partyLess->customer_id = $request->customer_id;
            $partyLess->sale_type_status = $customer_type;
            $partyLess->company_branch_id = $request->branch_id;
            $partyLess->transaction_method = 2;
            $partyLess->amount = $request->payment_discount;
            $partyLess->remaining_due = $customer->due - $totalAmount;
            $partyLess->date = $request->date;
            $partyLess->note = $request->note;
            $partyLess->save();
        }
        if ($request->type == 'retail_sale'){
            return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('sale_receipt.payment_details', ['payment' => $payment->id,'type'=>'retail_sale'])]);
        }elseif ($request->type == 'whole_sale'){
            return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('sale_receipt.payment_details', ['payment' => $payment->id,'type'=>'whole_sale'])]);
        }
    }

 public function makePaymentOld(Request $request) {
        //dd($request->all());
        $rules = [
            'customer_id' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'payment_discount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == '2') {
            //$rules['bank'] = 'required';
            //$rules['branch'] = 'required';
            $rules['cheque_no'] = 'required|string|max:255';
            $rules['cheque_amount'] = 'required';
            $rules['cheque_date'] = 'required|image';
        }

         if ($request->order != '') {
             $order = SalesOrder::find($request->order);
             $rules['amount'] = 'required|numeric|min:0|max:'.$order->due;
         }

         if ($request->customer_id != '') {
             $customer = Customer::find($request->customer_id);
             $rules['amount'] = 'required|numeric|min:0|max:'.$customer->due;
         }

//         if ($request->order != '') {
//             if ($request->amount < $order->due)
//                 $rules['next_payment_date'] = 'required|date';
//         }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $totalAmount = $request->amount + $request->payment_discount;
        $customer = Customer::find($request->customer_id);

        if ($request->payment_type == 1) {
            $payment = new SalePayment();
            $payment->sales_order_id = null;
            $payment->customer_id = $request->customer_id;
            $payment->company_branch_id = $request->branch_id;
            $payment->transaction_method = $request->payment_type;
            $payment->amount = $request->amount;
            $payment->remaining_due = $customer->due - $totalAmount;
            $payment->payment_discount = $request->payment_discount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->status = 2;
            $payment->save();

            //Balance Increment
            BranchCash::where('company_branch_id',$customer->company_branch_id)->first()->increment('amount', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Payment from ' . $customer->name;
            $log->transaction_type = 1;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->customer_id = $request->customer_id;
            $log->sale_payment_id = $payment->id;
            $log->company_branch_id = $customer->company_branch_id;
            $log->save();
        }else {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/sales_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/sales_payment_cheque/'.$filename;
            }

            $payment = new SalePayment();
            $payment->sales_order_id = null;
            $payment->customer_id = $request->customer_id;
            $payment->company_branch_id = $customer->company_branch_id;
            $payment->transaction_method = 2;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->cheque_image = $image;
            $payment->amount = $request->amount;
            $payment->remaining_due = $customer->due - $totalAmount;
            $payment->payment_discount = $request->payment_discount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->status = 1;
            $payment->save();

//            BankAccount::find($request->account)->increment('balance',  $request->amount);
//
//            $log = new TransactionLog();
//            $log->date = $request->date;
//            $log->particular = 'Payment from ' . $customer->name;
//            $log->transaction_type = 1;
//            $log->transaction_method = 2;
//            $log->account_head_type_id = 2;
//            $log->account_head_sub_type_id = 2;
//            $log->bank_id = $request->bank;
//            $log->branch_id = $request->branch;
//            $log->bank_account_id = $request->account;
//            $log->cheque_no = $request->cheque_no;
//            $log->cheque_image = $image;
//            $log->amount = $request->amount;
//            $log->note = $request->note;
//            $log->customer_id = $request->customer_id;
//            $log->sale_payment_id = $payment->id;
//            $log->company_branch_id = $customer->company_branch_id;
//            $log->save();
        }

        if($request->payment_discount > 0){
            $partyLess = new PartyLess();
            $partyLess->customer_id = $request->customer_id;
            $partyLess->company_branch_id = $request->branch_id;
            $partyLess->transaction_method = 2;
            $partyLess->amount = $request->payment_discount;
            $partyLess->remaining_due = $customer->due - $totalAmount;
            $partyLess->date = $request->date;
            $partyLess->note = $request->note;
            $partyLess->save();
        }

        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('sale_receipt.payment_details', ['payment' => $payment->id])]);
    }

    public function voucherUpdate(Request $request) {
        $rules = [
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $payment = SalePayment::where('id', $request->payment_id)->where('status',2)->first();


        if ($payment){
            if ($payment->transaction_method == 1 || $payment->transaction_method == 5) {
                //Cash::first()->decrement('amount', $payment->amount);
                //Cash::first()->increment('amount', $request->amount);
                BranchCash::where('company_branch_id',$payment->company_branch_id)->first()->decrement('amount', $payment->amount);
                BranchCash::where('company_branch_id',$payment->company_branch_id)->first()->increment('amount', $request->amount);
            }elseif($payment->transaction_method == 2){
                BankAccount::find($payment->bank_account_id)->decrement('balance', $payment->amount);
                BankAccount::find($payment->bank_account_id)->increment('balance', $request->amount);
            }else{
                '';
            }
        }
        $payment->amount = $request->amount;
        $payment->note = $request->note;
        $payment->user_id = Auth::user()->id;
        $payment->save();

        $log = TransactionLog::where('sale_payment_id',$request->payment_id)->first();
        if ($log) {
            $log->amount = $request->amount;
            //$log->company_branch_id = Auth::user()->company_branch_id;
            $log->note = $request->note;
            $log->save();
        }

        return response()->json(['success' => true, 'message' => 'Payment has been Updated.', 'redirect_url' => route('sale_receipt.payment_details', ['payment' => $payment->id])]);
    }

    public function chequeApproved(Request $request) {

        $rules = [
            'payment_id' => 'required',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == 2) {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if ($request->payment_type == 1) {
            $previousPayment = SalePayment::with('customer')->where('id',$request->payment_id)->first();

            $payment = new SalePayment();
            if($previousPayment->sales_order_id != null){
                $payment->sales_order_id = $previousPayment->sales_order_id;
            }else{
                $payment->sales_order_id = null;
            }
            $payment->customer_id = $previousPayment->customer_id;
            $payment->company_branch_id = Auth::user()->company_branch_id;
            $payment->transaction_method = $request->payment_type;
            $payment->amount = $previousPayment->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->status = 2;
            $payment->save();

            if ($request->payment_type == 1)
                Cash::first()->increment('amount', $previousPayment->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Pending Cheque CashIn From ' . $previousPayment->customer->name;
            $log->transaction_type = 1;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->amount = $previousPayment->amount;
            $log->note = $request->note;
            $log->customer_id = $previousPayment->customer_id;
            $log->sale_payment_id = $payment->id;
            $log->company_branch_id = Auth::user()->company_branch_id;
            $log->payment_cheak_status = 3;
            $log->save();

            $previousPayment->update(
                ['status' => 3,
                 'note'=>"Cheque in by Cash",
                ]
            );

        }else{
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/sales_payment_cheque';
                $file->move($destinationPath, $filename);
                $image = 'uploads/sales_payment_cheque/'.$filename;
            }

            $payment = SalePayment::where('id',$request->payment_id)->first();

            $bankAccount = BankAccount::where('id', $request->account)
                ->first();

            if (empty($bankAccount)) {
                return response()->json(['success' => false, 'message' => 'Storage Bank Not Found', 'redirect_url' => route('customer_payments', ['customer_id' => $payment->customer->id])]);
            }


            if ($request->note == null) {
                if($payment->sales_order_id != null){
                    $payment->sales_order_id = $payment->sales_order_id;
                }else{
                    $payment->sales_order_id = null;
                }
                $payment->update(['status' => 2]);
                $payment->bank_id = $request->bank;
                $payment->branch_id = $request->branch;
                $payment->bank_account_id = $request->account;
                $payment->cheque_no = $request->cheque_no;
                $payment->cheque_image = $image;
                $payment->save();
            }else{
                $payment->update(['status' => 2 , 'note' => $request->note]);
                $payment->bank_id = $request->bank;
                $payment->branch_id = $request->branch;
                $payment->bank_account_id = $request->account;
                $payment->cheque_no = $request->cheque_no;
                $payment->cheque_image = $image;
                $payment->save();
            }

            $order = SalesOrder::where('id',$payment->sales_order_id)->first();
            if ($order) {
                $order->decrement('due',$payment->amount);
                $order->increment('paid',$payment->amount);
                $order->save();
            }


            $bankAccount->increment('balance', $payment->amount);

            $log = TransactionLog::where('sale_payment_id',$payment->id)->where('payment_cheak_status',1)->first();
            $log->bank_id = $request->bank;
            $log->branch_id = $request->branch;
            $log->bank_account_id = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->payment_cheak_status = 2;
//            $log->amount = $payment->amount;
//            $log->note = $payment->note;
//            $log->customer_id = $payment->customer->id;
//            $log->sale_payment_id = $payment->id;
            $log->save();
        }

        return response()->json(['success' => true, 'message' => 'Cheque has been completed.', 'redirect_url' => route('customer_payments', ['customer_id' => $payment->customer->id])]);
    }

    public function saleProductDetails(Request $request) {
        $firstWarehouseProduct = PurchaseInventory::where('serial', $request->serial)
            ->where('warehouse_id', 1)
            ->where('quantity', '>=', 0)
            ->with('productItem','productCategory','warehouse')
            ->first();

        $secondWarehouseProduct = PurchaseInventory::where('serial', $request->serial)
            ->where('warehouse_id', 2)
            ->where('quantity', '>=', 0)
            ->with('productItem','productCategory','warehouse')
            ->first();

        if (!empty($firstWarehouseProduct && $secondWarehouseProduct)) {

            if ($firstWarehouseProduct->quantity > $secondWarehouseProduct->quantity) {
                $product = $firstWarehouseProduct->toArray();
                return response()->json(['success' => true, 'data' => $product]);
            }elseif($firstWarehouseProduct->quantity < $secondWarehouseProduct->quantity){
                $product = $secondWarehouseProduct->toArray();
                return response()->json(['success' => true, 'data' => $product]);
            }elseif($firstWarehouseProduct->quantity == $secondWarehouseProduct->quantity){
                $product = $firstWarehouseProduct->toArray();
                return response()->json(['success' => true, 'data' => $product]);
            }else{
                return response()->json(['success' => false, 'message' => 'Not found.']);
            }

        }else{

            $product = PurchaseInventory::where('serial', $request->serial)
                ->where('quantity', '>', 0)
                //->where('selling_price', '>', 1)
                ->with('productItem','productCategory','warehouse')
                ->first();

            if ($product) {
                if ($product->selling_price > 0) {
                    $product = $product->toArray();
                    return response()->json(['success' => true, 'data' => $product]);
                } elseif($product->selling_price <= 0) {
                    //dd($product->selling_price);
                    return response()->json(['success' => false, 'message' => 'This Product Selling Price less than zero'.' .'.$product->serial]);
                }else{
                    return response()->json(['success' => false, 'message' => 'Product Not Available.']);
                }
            }else{
                return response()->json(['success' => false, 'message' => 'Product Not Available.']);
            }
        }

    }

    public function saleProductCheckDetails(Request $request) {
        $firstWarehouseProduct = PurchaseInventory::where('serial', $request->serial)
            ->where('warehouse_id', 1)
            //->where('quantity', '>=', 0)
            ->with('productItem','productCategory','warehouse')
            ->first();

        $secondWarehouseProduct = PurchaseInventory::where('serial', $request->serial)
            ->where('warehouse_id', 2)
            //->where('quantity', '>=', 0)
            ->with('productItem','productCategory','warehouse')
            ->first();

        if (!empty($firstWarehouseProduct && $secondWarehouseProduct)) {

            if ($firstWarehouseProduct->quantity > $secondWarehouseProduct->quantity) {
                $product = $firstWarehouseProduct->toArray();
                return response()->json(['success' => true, 'data' => $product]);
            }elseif($firstWarehouseProduct->quantity < $secondWarehouseProduct->quantity){
                $product = $secondWarehouseProduct->toArray();
                return response()->json(['success' => true, 'data' => $product]);
            }else{
                return response()->json(['success' => false, 'message' => 'Not found.']);
            }

        }else{

            $product = PurchaseInventory::where('serial', $request->serial)
                //->where('quantity', '>', 0)
                //->where('selling_price', '>', 1)
                ->with('productItem','productCategory','warehouse')
                ->first();

            if ($product) {
                if ($product->selling_price > 0) {
                    $product = $product->toArray();
                    return response()->json(['success' => true, 'data' => $product]);
                } elseif($product->selling_price <= 0) {
                    //dd($product->selling_price);
                    return response()->json(['success' => false, 'message' => 'This Product Selling Price less than zero'.' .'.$product->serial]);
                }else{
                    return response()->json(['success' => false, 'message' => 'Product Not Available.']);
                }
            }else{
                return response()->json(['success' => false, 'message' => 'Product Not Available.']);
            }
        }

    }

    public function customerJson(Request $request) {
        if (!$request->searchTerm) {
            $customers = Customer::orderBy('name')->where('status', 1)->limit(10)->get();
        } else {
            $customers = Customer::where(function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->searchTerm.'%')
                        ->orWhere('mobile', 'like', '%'.$request->searchTerm.'%');
                })->orderBy('name')->where('status', 1)->limit(10)->get();
        }

        $data = array();

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->id,
                'text' => $customer->name.' - '.$customer->mobile_no
            ];
        }

        echo json_encode($data);
    }

    public function supplierJson(Request $request) {
        if (!$request->searchTerm) {
            $suppliers = Supplier::where('status', 1)->orderBy('name')->limit(10)->get();
        } else {
            $suppliers = Supplier::where('status', 1)
                ->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->searchTerm.'%');
                })->orderBy('name')->limit(10)->get();
        }

        $data = array();

        foreach ($suppliers as $supplier) {
            $data[] = [
                'id' => $supplier->id,
                'text' => $supplier->name
            ];
        }

        echo json_encode($data);
    }



    public function saleReceiptEdit(SalesOrder $order) {
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $productItems = ProductItem::where('status', 1)->orderBy('name')->get();
        $customer = Customer::find($order->customer_id);
        $companyBranches = CompanyBranch::where('status', 1)->orderBy('name')->get();
        return view('sale.receipt.edit', compact('order', 'warehouses',
            'banks', 'customer','productItems','order','companyBranches'));
    }

    public function saleReceiptWarehousePendingEdit(SalesOrder $order) {

        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $productItems = ProductItem::where('status', 1)->orderBy('name')->get();
        $customers = Customer::orderBy('name')->where('status', 1)->get();
        return view('sale.receipt.warehouse_pending_edit', compact('order', 'warehouses',
            'banks', 'customers','productItems','order'));
    }

    public function saleReceiptWarehousePendingEditPost(SalesOrder $order, Request $request){

        if ($order->invoice_type == 1) {
            $order->invoice_type = 2;
            $order->save();
            $message = 'Warehouse Approved Successfully';
            return redirect()->route('sale_receipt.customer.warehouse_pending.all')->withInput()->with('message', $message);
            //return response()->json(['success' => true, 'message' => 'Warehouse Approved Successfully.', 'redirect_url' => route('sale_receipt.customer.warehouse_pending.all')]);
        }

    }

    public function saleReceiptEditPost(SalesOrder $order, Request $request){

        if (empty($request->product_serial)) {
            $message = 'No Product Item Found';
            return redirect()->back()->withInput()->with('message', $message);
        }

        if ($request->payment_type == '2') {
            $salePayment = SalePayment::where('sales_order_id', $order->id)
                ->where('transaction_method', 2)
                ->first();
            if ($salePayment) {
                if ($salePayment->status == 2 || $salePayment->status == 3) {
                    $message = 'This Order Cheque Already Approved';
                    return redirect()->back()->withInput()->with('message', $message);
                }
        //     } else {
        //         $paidTotal = $request->paid + $request->client_amount;
        //         if ($request->paid > $request->total || $request->client_amount > $request->total || $paidTotal > $request->total) {
        //             $message = 'Paid Amount Should Not Greater Than Total';
        //             return redirect()->back()->withInput()->with('message', $message);
        //         }
        //     }
        // } else {

        //     if ($request->paid > $request->total) {
        //         $message = 'Paid Amount Should Not Greater Than Total';
        //         return redirect()->back()->withInput()->with('message', $message);
            }
        }
        $rules = [
            'customer' => 'required',
            'date' => 'required|date',
        ];

        if (Auth::user()->company_branch_id == 0) {
            $rules['companyBranch'] = 'required';
        }

        if ($request->product_serial) {
            $rules['product_serial.*'] = 'required';
            $rules['product_item.*'] = 'required';
            $rules['product_category.*'] = 'required';
            $rules['warehouse.*'] = 'required';
            $rules['quantity.*'] = 'required|numeric|min:.01';
            $rules['unit_price.*'] = 'required|numeric|min:0';
        }
//        if ($order->invoice_type == 1){
//            $rules['invoice_type'] = 'required';
//        }

        if ($request->payment_type == '2') {
            $rules['client_bank_name'] = 'required';
            $rules['client_cheque_no'] = 'required';
            $rules['cheque_date'] = 'required';
            $rules['client_amount'] = 'required';
        }

        $request->validate($rules);

        $available = true;
        $message = '';

        if ($request->purchase_inventory) {
            foreach ($request->purchase_inventory as $index => $purchase_inventory_id) {
                $inventory = PurchaseInventory::find($purchase_inventory_id);
                $prev_quantity = SalesOrderProduct::where('sales_order_id', $order->id)
                        ->where('purchase_inventory_id', $purchase_inventory_id)
                        ->first()->quantity ?? 0;

                //dd($inventory);
                if ($inventory) {
                    if ($request->quantity[$index] > ($inventory->quantity + $prev_quantity)) {
                        $available = false;
                        $message = 'Insufficient' . $inventory->serial;
                        break;
                    }else{
                        if ($request->quantity[$index] > $prev_quantity ) {

                           $decrementQuantity = $request->quantity[$index] - $prev_quantity;
                           $inventory->decrement('quantity',$decrementQuantity);

                        }elseif($request->quantity[$index] < $prev_quantity){
                            $incrementQuantity = $prev_quantity - $request->quantity[$index];

                            $inventory->increment('quantity',$incrementQuantity);
                        }
                    }
                }

            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }
        $oldBranchId = $order->company_branch_id;
        $oldPaid = $order->paid;
        //dd('bfb');
        if (Auth::user()->company_branch_id == 0) {
            $order->company_branch_id = $request->companyBranch;
        }else{
            $order->company_branch_id = Auth::user()->company_branch_id;
        }

        if ($order->invoice_type == 1) {
            $order->invoice_type = 1;
        }
        $order->customer_id = $request->customer;
        $order->received_by = $request->received_by;
        $order->date = $request->date;
        $order->note = $request->note;
        $order->vat_percentage = $request->vat;
        $order->discount_percentage = $request->discount_percentage;
        $order->discount = $request->discount;
        $order->sale_adjustment = $request->sale_adjustment;
        $order->transport_cost = $request->transport_cost;
        $order->return_amount = $request->return_amount;
        $order->sale_type = $request->sale_type;
        $order->previous_due = $request->previous_due;
        $order->current_due = $request->due_total;
        $order->save();

        PurchaseInventoryLog::where('sales_order_id', $order->id)
            ->delete();

        $prev_order_products = SalesOrderProduct::where('sales_order_id', $order->id)
            ->whereNotIn('serial', $request->product_serial)
            ->get();

        foreach ($prev_order_products as $prev_order_product){
            $purchaseInventory = PurchaseInventory::where('id',$prev_order_product->purchase_inventory_id)->first();
            $purchaseInventory->increment('quantity', $prev_order_product->quantity);
            $prev_order_product->delete();
        }
        SalesOrderProduct::where('sales_order_id', $order->id)
            ->delete();

        $subTotal = 0;
        if ($request->purchase_inventory) {
            foreach ($request->purchase_inventory as $key => $purchase_inventory_id) {
                $inventory = PurchaseInventory::find($purchase_inventory_id);
                if ($inventory) {
                    $sales_order_product = SalesOrderProduct::create([
                        'sales_order_id' => $order->id,
                        'purchase_inventory_id' => $purchase_inventory_id,
                        'product_item_id' => $inventory->product_item_id,
                        'product_category_id' => $inventory->product_category_id,
                        'warehouse_id' => $inventory->warehouse_id,
                        'serial' => $inventory->serial,
                        'quantity' => $request->quantity[$key],
                        'buy_price' => $inventory->unit_price,
                        'unit_price' => $request->unit_price[$key],
                        'total' => $request->quantity[$key] * $request->unit_price[$key],
                    ]);

                    $subTotal += $request->quantity[$key] * $request->unit_price[$key];

                    $inventoryLog = new PurchaseInventoryLog();
                    $inventoryLog->product_item_id = $inventory->product_item_id;
                    $inventoryLog->product_category_id = $inventory->product_category_id;
                    $inventoryLog->warehouse_id = $inventory->warehouse_id;
                    $inventoryLog->serial = $inventory->serial;
                    $inventoryLog->type = 2;
                    $inventoryLog->date = $request->date;
                    $inventoryLog->quantity = $request->quantity[$key];
                    $inventoryLog->unit_price = $inventory->unit_price;
                    $inventoryLog->selling_price = $request->unit_price[$key];
                    $inventoryLog->sale_total = $request->quantity[$key] * $inventoryLog->selling_price;
                    $inventoryLog->total = $request->quantity[$key] * $inventoryLog->unit_price;
                    $inventoryLog->sales_order_id = $order->id;
                    $inventoryLog->purchase_inventory_id = $inventory->id;
                    $inventoryLog->note = 'Sale Product';
                    $inventoryLog->user_id = Auth::id();
                    $inventoryLog->save();

                    //$inventory->decrement('quantity', $request->quantity[$key]);

//                    $inventory->update([
//                        'quantity' => $inventory->in_product - $inventory->out_product,
//                    ]);
                }
            }
        }

        if ($request->paid > 0) {

            if ($order->paid > 0) {

                if ($order->paid < $request->paid) {
                    $paidIncrement = $request->paid - $order->paid;
                    $purchasePaymentCheck = SalePayment::where('sales_order_id', $order->id)->where('transaction_method', 1)->first();
                    if ($purchasePaymentCheck) {
                        $purchasePaymentCheck->increment('amount', $paidIncrement);
                        //Cash::first()->increment('amount', $paidIncrement);
                        if(\Illuminate\Support\Carbon::now()->format('Y-m-d') == $order->date->format('Y-m-d')){
                            $purchasePaymentCheck->received_type = 1;
                            $purchasePaymentCheck->save();
                        }else{
                            $purchasePaymentCheck->received_type = 2;
                            $purchasePaymentCheck->save();
                        }
                    }
                    $log = TransactionLog::where('sale_payment_id',$purchasePaymentCheck->id)->where('transaction_method', 1)->first();
                    if ($log) {
                        $log->increment('amount', $paidIncrement);
                        $log->date = $request->date;
                        $log->save();
                    }

                } else {
                    $paidDecrement = $order->paid - $request->paid;
                    $purchasePaymentCheck = SalePayment::where('sales_order_id', $order->id)->where('transaction_method', 1)->first();
                    if ($purchasePaymentCheck) {
                        $purchasePaymentCheck->decrement('amount', $paidDecrement);
                        //Cash::first()->decrement('amount', $paidDecrement);
                        if(\Illuminate\Support\Carbon::now()->format('Y-m-d') == $order->date->format('Y-m-d')){
                            $purchasePaymentCheck->received_type = 1;
                            $purchasePaymentCheck->save();
                        }else{
                            $purchasePaymentCheck->received_type = 2;
                            $purchasePaymentCheck->save();
                        }
                    }
                    $log = TransactionLog::where('sale_payment_id',$purchasePaymentCheck->id)->where('transaction_method', 1)->first();
                    if ($log) {
                        $log->decrement('amount', $paidDecrement);
                        $log->date = $request->date;
                        $log->save();
                    }
                }
            } else {
                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->customer_id = $request->customer;
                $payment->company_branch_id = $order->company_branch_id;
                $payment->transaction_method = 1;
                $payment->received_type = 1;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->status = 2;
                $payment->save();

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Rev. from ' . ($order->order_no) . ' ' . $order->customer->name;
                $log->transaction_type = 1;
                $log->transaction_method = 1;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->amount = $request->paid;
                $log->customer_id = $request->customer ?? null;
                $log->sale_payment_id = $payment->id;
                $log->company_branch_id = $order->company_branch_id;
                $log->sales_order_id = $order->id;
                $log->save();

                //Cash::first()->increment('amount', $request->paid);
            }
        }

        //BranchCash::where('company_branch_id',$oldBranchId)->first()->decrement('amount', $oldPaid);
        //BranchCash::where('company_branch_id',$order->company_branch_id)->first()->increment('amount', $request->paid);
        Cash::first()->decrement('amount', $oldPaid);
        Cash::first()->increment('amount', $request->paid);

        $order->sub_total = $subTotal ;
        $vat = ($subTotal * $request->vat) / 100;
        $order->vat = $vat;
        $total =  (($subTotal + $request->transport_cost + $vat) - $request->discount) - $request->return_amount-$request->sale_adjustment;
        $order->total = $total;
        $order->paid = $request->paid;
        $due = $total - $request->paid;
        $order->invoice_total = $total > $request->paid ? $request->paid : $total;
        $order->due = $due;
        $order->next_payment = $due > 0 ? $request->next_payment : null;

        if ($order->client_amount > 0) {

            if ($request->payment_type == 2) {

                $order->client_bank_name = $request->client_bank_name;
                $order->client_cheque_no = $request->client_cheque_no;
                $order->cheque_date = $request->cheque_date;
                $order->client_amount = $request->client_amount;

                $salePayment = SalePayment::where('sales_order_id',$order->id)->where('status', 1)->first();

                if ($salePayment) {
                    $salePayment->client_bank_name = $request->client_bank_name;
                    $salePayment->client_cheque_no = $request->client_cheque_no;
                    $salePayment->cheque_date = $request->cheque_date;
                    $salePayment->client_amount = $request->client_amount;
                    $salePayment->amount = $request->client_amount;
                    $salePayment->save();

                }
                $log = TransactionLog::where('sale_payment_id',$salePayment->id)->first();
                $log->date = $request->date;
                $log->amount = $request->client_amount;
                $log->save();

            }
        }else {

            if ($request->payment_type == 2) {

                $order->client_bank_name = $request->client_bank_name;
                $order->client_cheque_no = $request->client_cheque_no;
                $order->cheque_date = $request->cheque_date;
                $order->client_amount = $request->client_amount;

                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->customer_id = $request->customer;
                $payment->company_branch_id = $order->company_branch_id;
                $payment->transaction_method = 2;
                $payment->client_bank_name = $request->client_bank_name;
                $payment->client_cheque_no = $request->client_cheque_no;
                $payment->client_amount = $request->client_amount;
                $payment->amount = $request->client_amount;
                $payment->date = $request->date;
                $payment->cheque_date = $request->cheque_date;
                $payment->note = $request->note;
                $payment->status = 1;
                $payment->save();


                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Rev. from ' . ($order->order_no) . ' ' . $order->customer->name;
                $log->transaction_type = 1;
                $log->transaction_method = 2;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->amount = $request->client_amount;
                $log->customer_id = $customer->id ?? null;
                $log->company_branch_id = $order->company_branch_id;
                $log->sales_order_id = $order->id;
                $log->sale_payment_id = $payment->id;
                $log->payment_cheak_status = 1;
                $log->save();
            }
        }
        $order->save();

        return redirect()->route('sale_receipt.details', ['order' => $order->id]);
    }

    public function customerPayments($customer_id){
        $customer = Customer::find($customer_id);
        $banks = Bank::where('status',1)->orderBy('name')->get();
        $payments = SalePayment::where('customer_id', $customer_id)->with('salesOrder')->orderBy('date','desc')->paginate(10);
        $due = SalesOrder::where('customer_id', $customer_id)->orderBy('date','desc')->paginate(10);
        //$trashPayments = SalePayment::withTrashed()->get();
        return view('sale.client_payment.customer_payments', compact('customer',
            'payments','banks','due'));
    }

    public function salePaymentTrashView(Customer $customer){
        $trashPayments = SalePayment::onlyTrashed()->paginate(10);
        return view('sale.client_payment.customer_payment_trash_view', compact('trashPayments','customer'));
    }

    public function allPendingCheque(){
        $currentDate = date('Y-m-d');
        $banks = Bank::where('status',1)->orderBy('name')->get();
        if (\request()->get('type') == 'retail_sale'){
            $type = 1;
        }elseif (\request()->get('type') == 'whole_sale'){
            $type = 2;
        }
//        if(Auth::user()->company_branch_id==1){
//            $payments = SalePayment::where('status', 1)->where('company_branch_id',1)->paginate(10);
//        }elseif (Auth::user()->company_branch_id==2){
//            $payments = SalePayment::where('status', 1)->where('company_branch_id',2)->paginate(10);
//        }else{
//            $payments = SalePayment::where('status', 1)->paginate(10);
//        }
        $payments = SalePayment::where('sale_type_status',$type)->where('status', 1)->paginate(10);
        return view('sale.client_payment.all_pending_cheque',compact('payments','banks','currentDate'));
    }

    public function todayPendingCheque(){
        $currentDate = date('Y-m-d');
        $banks = Bank::where('status',1)->orderBy('name')->get();
        if (\request()->get('type') == 'retail_sale'){
            $type = 1;
        }elseif (\request()->get('type') == 'whole_sale'){
            $type = 2;
        }
//        if(Auth::user()->company_branch_id==1){
//            $payments = SalePayment::where('status', 1)->where('company_branch_id',1)->paginate(10);
//        }elseif (Auth::user()->company_branch_id==2){
//            $payments = SalePayment::where('status', 1)->where('company_branch_id',2)->paginate(10);
//        }else{
//            $payments = SalePayment::where('status', 1)->paginate(10);
//        }
        $payments = SalePayment::where('sale_type_status',$type)->whereDate('cheque_date',$currentDate)->where('status', 1)->paginate(10);
        return view('sale.client_payment.today_pending_cheque',compact('payments','banks','currentDate'));
    }

    public function approveCheque(Request $request){
        $payment = SalePayment::where('id',$request->id)->first();
        $customer = Customer::find($payment->customer_id);
//        dd($payment);
        if ($request->type == 'retail_sale'){
            $type = 1;
        }elseif ($request->type == 'whole_sale'){
            $type = 2;
        }
        //BankAccount::find($payment->bank_account_id)->increment('balance',  $payment->amount);
        Cash::first()->increment('amount',  $payment->amount);

            $log = new TransactionLog();
            $log->date = $payment->date;
            $log->particular = 'Payment from ' . $customer->name;
            $log->transaction_type = 1;
            $log->transaction_method = 1;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            //$log->bank_id = $payment->bank_id;
            //$log->branch_id = $payment->branch_id;
            //$log->bank_account_id = $payment->bank_account_id;
            $log->cheque_no = $payment->client_cheque_no;
            $log->cheque_image = $payment->cheque_image;
            $log->amount = $payment->amount;
            $log->note = $payment->note;
            $log->customer_id = $customer->id;
            $log->sale_type_status = $type;
            $log->sale_payment_id = $payment->id;
            $log->company_branch_id = $customer->company_branch_id;
            $log->save();


//        if($payment->payment_discount > 0){
//            $partyLess = new PartyLess();
//            $partyLess->customer_id = $customer->id;
//            $partyLess->company_branch_id = $payment->company_branch_id;
//            $partyLess->transaction_method = 2;
//            $partyLess->amount = $payment->payment_discount;
//            $partyLess->remaining_due = $payment->remaining_due;
//            $partyLess->date = $payment->date;
//            $partyLess->note = $payment->note;
//            $partyLess->save();
//        }

        $payment->status = 2;
        $payment->save();

        return response()->json(['success' => true, 'message' => 'Approve Successfully.']);
    }

    public function allPendingCash(){
        $currentDate = date('Y-m-d');
        $banks = Bank::where('status',1)->orderBy('name')->get();
        if (\request()->get('type') == 'retail_sale'){
            $type = 1;
        }elseif (\request()->get('type') == 'whole_sale'){
            $type = 2;
        }
        $payments = SalePayment::where('sale_type_status',$type)->where('transaction_method',1)->where('status', 1)->paginate(10);
        return view('sale.client_payment.all_pending_cash',compact('payments','banks','currentDate'));
    }

    public function todayPendingCash(){
        $currentDate = date('Y-m-d');
        $banks = Bank::where('status',1)->orderBy('name')->get();
        if (\request()->get('type') == 'retail_sale'){
            $type = 1;
        }elseif (\request()->get('type') == 'whole_sale'){
            $type = 2;
        }
        $payments = SalePayment::where('sale_type_status',$type)->where('transaction_method',1)->whereDate('date',$currentDate)->where('status', 1)->paginate(10);
        return view('sale.client_payment.today_pending_cash',compact('payments','banks','currentDate'));
    }

    public function approveCash(Request $request){
        $payment = SalePayment::where('id',$request->id)->first();
        $customer = Customer::find($payment->customer_id);
//        dd($payment);
        if ($request->type == 'retail_sale'){
            $type = 1;
        }elseif ($request->type == 'whole_sale'){
            $type = 2;
        }
        //BankAccount::find($payment->bank_account_id)->increment('balance',  $payment->amount);
        Cash::first()->increment('amount',  $payment->amount);

            $log = new TransactionLog();
            $log->date = $payment->date;
            $log->particular = 'Payment from ' . $customer->name;
            $log->transaction_type = 1;
            $log->transaction_method = 1;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            //$log->bank_id = $payment->bank_id;
            //$log->branch_id = $payment->branch_id;
            //$log->bank_account_id = $payment->bank_account_id;
            //$log->cheque_no = $payment->client_cheque_no;
            //$log->cheque_image = $payment->cheque_image;
            $log->amount = $payment->amount;
            $log->note = $payment->note;
            $log->customer_id = $customer->id;
            $log->sale_type_status = $type;
            $log->sale_payment_id = $payment->id;
            $log->company_branch_id = $customer->company_branch_id;
            $log->save();


//        if($payment->payment_discount > 0){
//            $partyLess = new PartyLess();
//            $partyLess->customer_id = $customer->id;
//            $partyLess->company_branch_id = $payment->company_branch_id;
//            $partyLess->transaction_method = 2;
//            $partyLess->amount = $payment->payment_discount;
//            $partyLess->remaining_due = $payment->remaining_due;
//            $partyLess->date = $payment->date;
//            $partyLess->note = $payment->note;
//            $partyLess->save();
//        }

        $payment->status = 2;
        $payment->save();

        return response()->json(['success' => true, 'message' => 'Approve Successfully.']);
    }
    public function approveChequeOld(Request $request){
        $payment = SalePayment::where('id',$request->id)->first();
        $customer = Customer::find($payment->customer_id);
        BankAccount::find($payment->bank_account_id)->increment('balance',  $payment->amount);

            $log = new TransactionLog();
            $log->date = $payment->date;
            $log->particular = 'Payment from ' . $customer->name;
            $log->transaction_type = 1;
            $log->transaction_method = 2;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->bank_id = $payment->bank_id;
            $log->branch_id = $payment->branch_id;
            $log->bank_account_id = $payment->bank_account_id;
            $log->cheque_no = $payment->cheque_no;
            $log->cheque_image = $payment->cheque_image;
            $log->amount = $payment->amount;
            $log->note = $payment->note;
            $log->customer_id = $customer->id;
            $log->sale_payment_id = $payment->id;
            $log->company_branch_id = $customer->company_branch_id;
            $log->save();


        if($payment->payment_discount > 0){
            $partyLess = new PartyLess();
            $partyLess->customer_id = $customer->id;
            $partyLess->company_branch_id = $payment->company_branch_id;
            $partyLess->transaction_method = 2;
            $partyLess->amount = $payment->payment_discount;
            $partyLess->remaining_due = $payment->remaining_due;
            $partyLess->date = $payment->date;
            $partyLess->note = $payment->note;
            $partyLess->save();
        }

        $payment->status = 2;
        $payment->save();

        return response()->json(['success' => true, 'message' => 'Approve Successfully.']);
    }

    public function adminPendingCheque(){
        $currentDate = date('Y-m-d');
        $banks = Bank::where('status',1)->orderBy('name')->get();
        $payments = SalePayment::where('status', 1)->where('company_branch_id',0)->paginate(10);
        return view('sale.client_payment.admin_pending_cheque',compact('payments','banks','currentDate'));
    }
    public function yourChoicePendingCheque(){
        $currentDate = date('Y-m-d');
        $banks = Bank::where('status',1)->orderBy('name')->get();
        $payments = SalePayment::where('status', 1)->where('company_branch_id',1)->paginate(10);
        return view('sale.client_payment.your_choice_pending_cheque',compact('payments','banks','currentDate'));
    }
    public function yourChoicePlusPendingCheque(){
        $currentDate = date('Y-m-d');
        $banks = Bank::where('status',1)->orderBy('name')->get();
        $payments = SalePayment::where('status', 1)->where('company_branch_id',2)->paginate(10);
        return view('sale.client_payment.your_choice_plus_pending_cheque',compact('payments','banks','currentDate'));
    }

    public function pendingChequeDelete(Request $request){
        $salePayment = SalePayment::where('id', $request->salePaymentId)->where('status',1)->first();

        TransactionLog::where('sale_payment_id',$salePayment->id)->where('payment_cheak_status',1)->delete();

        $salesOrder = SalesOrder::where('id',$salePayment->sales_order_id)->first();
        if ($salesOrder) {
            $salesOrder->update([
                'client_bank_name' => null,
                'client_cheque_no' => null,
                'cheque_date' => null,
                'client_amount' => null,
            ]);
        }
        $salePayment->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Deleted'
        ]);
    }

    public function saleDelete(Request $request){

        $saleOrderProducts = SalesOrderProduct::where('sales_order_id', $request->id)->get();

        $saleOrderCheck = SalesOrder::where('id',$request->id)->first();

        foreach ($saleOrderProducts as $saleOrderProduct){
            $inventory = PurchaseInventory::where('id', $saleOrderProduct->purchase_inventory_id)
                ->first();
            if ($inventory){
                $inventory->increment('quantity',$saleOrderProduct->quantity);
            }

            $saleOrderProduct->delete();
        }

        $salePayments = SalePayment::where('sales_order_id',$request->id)->where('status', 2)->get();
        if (count($salePayments) > 0) {
            foreach ($salePayments as $salePayment){
                if ($salePayment->transaction_method == 1) {
                    Cash::first()->decrement('amount', $salePayment->amount);
                }else{
                    BankAccount::find($salePayment->bank_account_id)->decrement('balance', $salePayment->amount);
                }
                TransactionLog::where('sale_payment_id',$salePayment->id)->delete();
                $salePayment->delete();
            }
        }
        $cashPayments = SalePayment::where('sales_order_id',$request->id)->where('status', 3)->get();
        if (count($cashPayments) > 0) {
            foreach ($cashPayments as $cashPayment){
                if ($cashPayment->transaction_method == 1) {
                    Cash::first()->decrement('amount', $cashPayment->amount);
                }
                TransactionLog::where('sale_payment_id',$cashPayment->id)->delete();
                $cashPayment->delete();
            }
        }

        $pendingSalePayments = SalePayment::where('sales_order_id',$request->id)->where('status', 1)->get();
        if (count($pendingSalePayments) > 0) {
            foreach ($pendingSalePayments as $pendingSalePayment){
                TransactionLog::where('sale_payment_id',$pendingSalePayment->id)->delete();
                $pendingSalePayment->delete();
            }
        }
        if ($saleOrderCheck->invoice_type == 1 || $saleOrderCheck->invoice_type == 2){
            PurchaseInventoryLog::where('sales_order_id',$request->id)->delete();

            SalesOrder::where('id',$request->id)->delete();
        }

        return redirect(route('sale_receipt.customer.all'))->with('message','Sale Order Delete Successfully');
    }

    public function voucherDelete(Request $request){

        $salePayment = SalePayment::where('id',$request->id)->whereIn('status', [2,3])->first();
        if ($salePayment) {
            if ($salePayment->transaction_method == 1 || $salePayment->transaction_method == 5) {
                Cash::first()->decrement('amount', $salePayment->amount);
            }elseif ($salePayment->transaction_method == 2){
                BankAccount::find($salePayment->bank_account_id)->decrement('balance', $salePayment->amount);
            }else{
                '';
            }
            TransactionLog::where('sale_payment_id',$salePayment->id)->delete();
            $salePayment->delete();

        }

        $pendingSalePayment = SalePayment::where('id',$request->id)->where('status', 1)->first();
        if ($pendingSalePayment) {
            if($pendingSalePayment){
                TransactionLog::where('sale_payment_id',$pendingSalePayment->id)->delete();
                $pendingSalePayment->delete();
            }
        }
        return redirect(route('customer_payments', ['customer_id'=>$request->customer_id]))->with('message','Customer Payment Delete Successfully');
    }

    public function CustomerPaymentsDatatable() {
        $query = Customer::where('status', 1);

        return DataTables::eloquent($query)
            ->addColumn('action', function(Customer $customer) {
                $btn = '<a class="btn btn-info btn-sm btn-pay" role="button" data-id="'.$customer->id.'" data-name="'.$customer->name.'">Payment</a> ';
                $btn .= '<a class="btn btn-primary btn-sm" href="'.route("customer_payments",$customer->id).'"> Details </a> ';
                return $btn;
            })
            ->addColumn('total', function(Customer $customer) {
                return 'Tk '.number_format($customer->total, 2);
            })
            ->addColumn('paid', function(Customer $customer) {
                return 'Tk '.number_format($customer->paid, 2);
            })
            ->addColumn('due', function(Customer $customer) {
                return 'Tk '.number_format($customer->due, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }


    public function saleReceiptCustomerDatatable(){
        if (\request()->get('type')=='retail_sale'){
            $type = 1;
        }elseif (\request()->get('type')=='whole_sale'){
            $type = 2;
        }
        $query = SalesOrder::with(['customer','companyBranch'])->where('sale_type', 1)->where('type', $type);

        return DataTables::eloquent($query)
            ->addColumn('name', function (SalesOrder $order) {
                return $order->customer->name ?? '';
            })
            ->addColumn('mobile', function (SalesOrder $order) {
                return $order->customer->mobile_no ?? '';
            })
            ->addColumn('address', function (SalesOrder $order) {
                return $order->customer->address ?? '';
            })
            ->addColumn('company', function (SalesOrder $order) {
                return $order->companyBranch->name ?? '';
            })
            ->addColumn('quantity', function (SalesOrder $order) {
                return $order->quantity() ?? '';
            })
            ->addColumn('action', function (SalesOrder $order) {
                $action = '<a href="' . route('sale_receipt.details', ['order' => $order->id,'type'=>\request()->get('type')]) . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a> ';

                    $latestOrder = SalesOrder::where('customer_id', $order->customer_id)->latest('id')->first();
                    if ($order->id === $latestOrder->id) {
                        $action .= '<a href="' . route('sale_receipt.edit', ['order' => $order->id]) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    }

                if (Auth::user()->company_branch_id == 0) {
//                    $action .= '<a role="button" class="btn btn-danger btn-sm btnDelete" data-id="'.$order->id.'"> <i class="fa fa-trash"></i> </a>';
                }

                return $action;
            })
            ->addColumn('product_serials', function(SalesOrder $order) {
                $products = '';
                foreach ($order->products as $key => $product) {
                    $products .= $product->serial??'';
                    if(!empty($order->products[$key+1])){
                        $products .= ', ';
                    }
                }
                return $products;
            })
            ->filterColumn('product_serials', function ($query, $keyword) {
                //$order_products = ProductItem::where('name','like', '%'.$keyword.'%')->pluck('id');
                $order_ids = SalesOrderProduct::where('serial','like', '%'.$keyword.'%')->distinct('sales_order_id')->pluck('sales_order_id');
                return $query->whereIn('id', $order_ids);
            })

            ->editColumn('date', function (SalesOrder $order) {
                return $order->date->format('j F, Y');
            })
            ->editColumn('paid', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->paid, 2);
            })
            ->editColumn('due', function (SalesOrder $order) {
                if (\auth()->user()->role == 2)
                    return 'Tk ' . number_format(getSaleReceiptTotal($order) - $order->paid, 2);
                else
                    return 'Tk ' . number_format($order->current_due, 2);
            })

            ->editColumn('total', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->sub_total,2);
            })
            ->editColumn('return_amount', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->return_amount ?? '0',2);
            })
            ->editColumn('discount', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->discount,2);
            })
            ->editColumn('sale_adjustment', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->sale_adjustment ?? '0',2);
            })
            ->editColumn('transport_cost', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->transport_cost ?? '0',2);
            })
            ->editColumn('previous_due', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->previous_due ?? '0',2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }

    public function saleReceiptCustomerWarehousePendingDatatable()
    {
        if (Auth::user()->company_branch_id == 0) {
            $query = SalesOrder::with(['customer','companyBranch'])->where('sale_type', 1)
                ->where('invoice_type',1);

        }else{
            $query = SalesOrder::with(['customer','companyBranch'])->where('sale_type', 1)
                ->where('company_branch_id',Auth::user()->company_branch_id)
                ->where('invoice_type',1);
        }

        return DataTables::eloquent($query)
            ->addColumn('name', function (SalesOrder $order) {
                return $order->customer->name;
            })
            ->addColumn('mobile', function (SalesOrder $order) {
                return $order->customer->mobile_no ?? '';
            })
            ->addColumn('company', function (SalesOrder $order) {
                return $order->companyBranch->name ?? '';
            })
            ->addColumn('quantity', function (SalesOrder $order) {
                return $order->quantity() ?? '';
            })
            ->addColumn('action', function (SalesOrder $order) {
                $action = '<a href="' . route('sale_receipt.details', ['order' => $order->id]) . '" class="btn btn-info btn-sm">View</a> ';
                $action .= '<a href="' . route('sale_receipt_warehouse_pending.edit', ['order' => $order->id]) . '" class="btn btn-warning btn-sm">Pending</a>';
//                if (Auth::user()->company_branch_id == 0 && Auth::user()->id != 36) {
//                    $action .= '<a role="button" class="btn btn-danger btn-sm btnDelete" data-id="'.$order->id.'"> Delete </a>';
//                }

                return $action;
            })
            ->addColumn('product_serials', function(SalesOrder $order) {
                $products = '';
                foreach ($order->products as $key => $product) {
                    $products .= $product->serial??'';
                    if(!empty($order->products[$key+1])){
                        $products .= ', ';
                    }
                }
                return $products;
            })
            ->filterColumn('product_serials', function ($query, $keyword) {
                //$order_products = ProductItem::where('name','like', '%'.$keyword.'%')->pluck('id');
                $order_ids = SalesOrderProduct::where('serial','like', '%'.$keyword.'%')->distinct('sales_order_id')->pluck('sales_order_id');
                return $query->whereIn('id', $order_ids);
            })

            ->editColumn('date', function (SalesOrder $order) {
                return $order->date->format('j F, Y');
            })
            ->editColumn('paid', function (SalesOrder $order) {
                if (Auth::id() != 36) {
                    return 'Tk ' . number_format($order->paid , 2);
                }
            })
            ->editColumn('due', function (SalesOrder $order) {
                if (Auth::id() != 36) {
                    return 'Tk ' . number_format($order->current_due , 2);
                }
            })
            ->editColumn('total', function (SalesOrder $order) {
                if (Auth::id() != 36) {
                    if ($order->return_amount > 0 && $order->total == 0) {
                        return 'Tk ' . number_format($order->sub_total , 2);
                    } else {
                        return 'Tk ' . number_format($order->total , 2);
                    }
                }
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function saleWastageReceiptCustomerDatatable()
    {
        $query = SalesOrder::with(['customer','companyBranch'])->where('sale_type', 2);
//        if (Auth::user()->company_branch_id == 0) {
//            $query = SalesOrder::with(['customer','companyBranch'])->where('sale_type', 2);
//        }else{
//            $query = SalesOrder::with(['customer','companyBranch'])->where('sale_type', 2)
//                ->where('company_branch_id',Auth::user()->company_branch_id);
//        }

        return DataTables::eloquent($query)
            ->addColumn('name', function (SalesOrder $order) {
                return $order->customer->name;
            })
            ->addColumn('address', function (SalesOrder $order) {
                return $order->customer->address;
            })
            ->addColumn('company', function (SalesOrder $order) {
                return $order->companyBranch->name ?? '';
            })
            ->addColumn('quantity', function (SalesOrder $order) {
                return $order->quantity() ?? '';
            })
            ->addColumn('mobile', function (SalesOrder $order) {
                return $order->customer->mobile_no ?? '';
            })
            ->addColumn('status', function (SalesOrder $order) {
                if ($order->invoice_type == 1) {
                    return '<span class="label label-danger">warehouse not approved</span>';
                }else{
                    return '<span class="label label-success">warehouse approved</span>';
                }
            })
            ->addColumn('action', function (SalesOrder $order) {
                $action = '<a href="' . route('sale_receipt.details', ['order' => $order->id]) . '" class="btn btn-info btn-sm">View</a> ';
                $action .= '<a href="'.route('sale_receipt.edit', ['order' => $order->id]).'" class="btn btn-primary btn-sm">Edit</a>';

                return $action;
            })
            ->addColumn('product_serials', function(SalesOrder $order) {
                $products = '';
                foreach ($order->products as $key => $product) {
                    $products .= $product->serial??'';
                    if(!empty($order->products[$key+1])){
                        $products .= ', ';
                    }
                }
                return $products;
            })
            ->filterColumn('product_serials', function ($query, $keyword) {
                $order_ids = SalesOrderProduct::where('serial','like', '%'.$keyword.'%')->distinct('sales_order_id')->pluck('sales_order_id');
                return $query->whereIn('id', $order_ids);
            })

            ->editColumn('date', function (SalesOrder $order) {
                return $order->date->format('j F, Y');
            })
            ->editColumn('paid', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->paid, 2);
            })
            ->editColumn('due', function (SalesOrder $order) {
                if (\auth()->user()->role == 2)
                    return 'Tk ' . number_format(getSaleReceiptTotal($order) - $order->paid, 2);
                else
                    return 'Tk ' . number_format($order->current_due, 2);
            })
            ->editColumn('date', function (SalesOrder $order) {
                return $order->date->format('j F, Y');
            })
            ->editColumn('total', function (SalesOrder $order) {
                return 'Tk ' . number_format($order->total, 2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }

    public function clientPaymentCustomerDatatable(Request $request){
        //dd($request->all());
        $query = Customer::where('id',$request->customer);

        return DataTables::eloquent($query)
            ->addColumn('action', function (Customer $customer) {
//                if (Auth::user()->company_branch_id == 0 || Auth::user()->company_branch_id != 0) {
                    $btn = '<a class="btn btn-info btn-sm btn-pay" role="button" data-id="' . $customer->id . '" data-name="' . $customer->name . '" data-due="' . $customer->due . '" data-branch="' . $customer->branch->name . '" data-branchid="' . $customer->branch->id . '">Payment</a> ';
                    $btn .= '<a class="btn btn-primary btn-sm" href="' . route("customer_payments", $customer->id) . '"> Details </a> ';
                    return $btn;
//                }
            })
            ->addColumn('total', function (Customer $customer) {
                return 'Tk ' . number_format($customer->total , 2);
            })
//            ->addColumn('return', function (Customer $customer) {
//                return 'Tk ' . number_format($customer->return_amount , 2);
//            })
            ->addColumn('opening_due', function (Customer $customer) {
                return 'Tk ' . number_format($customer->opening_due , 2);
            })
            ->addColumn('branch', function (Customer $customer) {
                return $customer->branch->name??'';
            })
            ->addColumn('paid', function (Customer $customer) {
                return 'Tk ' . number_format($customer->paid , 2);
            })
            ->addColumn('due', function (Customer $customer) {
                return 'Tk ' . number_format($customer->due , 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
