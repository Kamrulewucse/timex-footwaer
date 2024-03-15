<?php

namespace App\Http\Controllers;

use App\Model\CompanyBranch;
use App\Model\Customer;
use App\Model\ProductCategory;
use App\Model\ProductColor;
use App\Model\ProductItem;
use App\Model\ProductReturnOrder;
use App\Model\ProductSize;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseOrderProduct;
use App\Model\SalesOrder;
use Carbon\Carbon;
use App\Model\Warehouse;
use App\Model\SalesOrderProduct;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class SalesReturnController extends Controller
{
    public function index(){
        {
            if (auth()->user()->company_branch_id == 0){
                $productReturnOrders = ProductReturnOrder::orderBy('id', 'desc')->with('logs','customer')->get();
            }else{
                $productReturnOrders = ProductReturnOrder::orderBy('id', 'desc')->with('logs','customer')->where('company_branch_id',auth()->user()->company_branch_id)->get();
            }
        }

        return view('sale.sales_return.invoice_all',compact('productReturnOrders'));
    }

    public function add(){
        $companyBranches = CompanyBranch::where('status', 1)->orderBy('name')->get();
        if(auth()->user()->role == 0 ){
            $salesOrders = SalesOrder::get();

        }else{
            $salesOrders = SalesOrder::where('company_branch_id',auth()->user()->company_branch_id)->with('companyBranch')->get();
        }
        return view('sale.sales_return.create',compact('salesOrders','companyBranches'));
    }

    public function addPost(Request $request)
    {
//        return($request->all());
        if (empty($request->product)) {
            $message = 'No Product Item Found';
            return redirect()->back()->withInput()->with('message', $message);
        }

        $rules = [
            'date' => 'required|date',
            'product.*' => 'required',
            'quantity.*' => 'required|numeric|min:1',
        ];

        $validator = $request->validate($rules);

        $saleOrder = SalesOrder::find($request->sale_order);

        $order = new ProductReturnOrder();
        $order->company_branch_id = $request->companyBranch;
        $order->customer_id = $saleOrder->customer_id;
        $order->date = Carbon::parse($request->start)->format('Y-m-d');
        $order->total = 0;
        $order->profit = 0;
        $order->save();
        $order->order_no = 'RO-'.str_pad($order->id, 6, 0, STR_PAD_LEFT);
        $order->save();

        $subTotal = 0;
        $profit = 0;

        foreach ($request->product as $key => $purchase_inventory_id) {
            $inventory = PurchaseInventory::find($request->inventory_id[$key]);
//            return($inventory);
            // Inventory Log
            $inventoryLog = new PurchaseInventoryLog();
            $inventoryLog->product_return_order_id = $order->id;
            $inventoryLog->customer_id = $saleOrder->customer_id;
            $inventoryLog->product_item_id = $inventory->product_item_id;
            $inventoryLog->product_category_id = $inventory->product_category_id;
            $inventoryLog->warehouse_id = $inventory->warehouse_id;
            $inventoryLog->serial = $inventory->serial;
            $inventoryLog->type = 1;
            $inventoryLog->date = Carbon::parse($request->start)->format('Y-m-d');
            $inventoryLog->quantity = $request->quantity[$key];
            $inventoryLog->unit_price = $inventory->unit_price;
            $inventoryLog->selling_price = $request->unit_price[$key];
            $inventoryLog->sale_total = $request->quantity[$key] * $request->unit_price[$key];
            $inventoryLog->total = $request->quantity[$key] * $request->unit_price[$key];
            $inventoryLog->sales_order_id = null;
            $inventoryLog->sales_order_no = $request->selected_return_order;
            $inventoryLog->purchase_inventory_id = $inventory->id;
            $inventoryLog->note = 'Sale Return Product';
            $inventoryLog->return_status = 1;
            $inventoryLog->user_id = Auth::id();
            $inventoryLog->company_branch_id = $request->companyBranch;
            $inventoryLog->save();

            $inventory->increment('quantity', $request->quantity[$key]);

            $subTotal += $request->quantity[$key] * $request->unit_price[$key];
            $profit += ($request->quantity[$key] * $request->unit_price[$key])-($inventory->avg_unit_price*$request->quantity[$key]);

        }
        $order->total = $subTotal;
        $order->profit = round($profit);
        $order->save();

        $saleOrder->return_status=1;
        $saleOrder->save();

        return redirect()->route('return_invoice.details', ['order' => $order->id]);

    }


    public function saleReturnProductDetails(Request $request) {

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

            if ($firstWarehouseProduct->quantity >= $secondWarehouseProduct->quantity) {
                $product = $firstWarehouseProduct->toArray();
                return response()->json(['success' => true, 'data' => $product]);
            }elseif($firstWarehouseProduct->quantity <= $secondWarehouseProduct->quantity){
                $product = $secondWarehouseProduct->toArray();
                return response()->json(['success' => true, 'data' => $product]);
            }else{
                return response()->json(['success' => false, 'message' => 'Not found.']);
            }

        }else{

            $product = PurchaseInventory::where('serial', $request->serial)
                ->where('quantity', '>=', 0)
                ->with('productItem','productCategory','warehouse')
                ->first();

            if ($product) {
                $product = $product->toArray();
                return response()->json(['success' => true, 'data' => $product]);
            } else {
                return response()->json(['success' => false, 'message' => 'Not found.']);
            }
        }


        if ($product) {
            $product = $product->toArray();
            return response()->json(['success' => true, 'data' => $product]);
        } else {
            return response()->json(['success' => false, 'message' => 'Not found.']);
        }
    }

    public function productReturnInvoiceAll(){
        $productReturnOrders = ProductReturnOrder::orderBy('id', 'desc')->with('logs','customer')->get();
        return view('sale.sales_return.invoice_all',compact('productReturnOrders'));
    }
    public function saleReturnTrashView(){
        $productReturnOrders = ProductReturnOrder::onlyTrashed()->paginate(10);
        return view('sale.sales_return.view_return_trash',compact('productReturnOrders'));
    }


    public function returnInvoiceDetails(ProductReturnOrder $order)
    {
        return view('sale.sales_return.invoice_details', compact('order'));
    }

    public function returnInvoicePrint(ProductReturnOrder $order) {
        return view('sale.sales_return.invoice_print', compact('order'));
    }

    public function returnInvoiceBarcode( ProductReturnOrder $order) {

        return view('sale.sales_return.barcode_all', compact('order'));
    }

    public function returnInvoiceBarcodePrint($order) {
        $product = PurchaseInventoryLog::where('id',$order)->first();

        return view('sale.sales_return.barcode_print', compact('product'));
    }

    public function edit(PurchaseInventoryLog $purchase_inventory_log)
    {
        $customers = Customer::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $productItems = ProductItem::where('status', 1)->orderBy('name')->get();
        $product_categories = ProductCategory::where('status', 1)->get();
        return view('sale.sales_return.edit', compact(
            'customers',
            'warehouses',
            'productItems',
            'product_categories',
            'purchase_inventory_log'
        ));
    }

    public function editPost(Request $request, PurchaseInventoryLog $purchase_inventory_log)
    {
        $rules = [
            'warehouse_id' => 'required',
            'date' => 'required|date',
            'product_item' => 'required',
            'product_category' => 'required',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ];

        $validator = $request->validate($rules);

        $purchase_inventory_log->customer_id = $request->customer;
        $purchase_inventory_log->date = $request->date;
        $purchase_inventory_log->quantity = $request->quantity;
        $purchase_inventory_log->selling_price = $request->unit_price;
        $purchase_inventory_log->sale_total = $request->quantity * $purchase_inventory_log->selling_price;
        $purchase_inventory_log->total = $request->quantity * $purchase_inventory_log->unit_price;
        $purchase_inventory_log->sales_order_no = $request->sales_order_no;
        $purchase_inventory_log->save();

        $inventory = $purchase_inventory_log->purchaseInventory;
        $inventory->update([
            'quantity' => $inventory->in_product - $inventory->out_product,
        ]);

        return redirect()->route('sales_return')->with('message', 'Return product updated successfully.');
    }

    public function details(PurchaseInventoryLog $purchase_inventory_log){
        return view('sale.sales_return.details',compact('purchase_inventory_log'));
    }
    public function receiptPrint(PurchaseInventoryLog $purchase_inventory_log){
        return view('sale.sales_return.print',compact('purchase_inventory_log'));
    }

    public function datatable()
    {
        if (Auth::user()->company_branch_id == 0) {
            $query = PurchaseInventoryLog::with(['customer','purchaseInventory', 'productItem', 'productCategory', 'warehouse'])
                ->where('return_status', 1)
                ->orderBy('date','DESC');

        }else{
            $query = PurchaseInventoryLog::with(['customer','purchaseInventory', 'productItem', 'productCategory', 'warehouse'])
                ->where('return_status', 1)
                ->where('company_branch_id', Auth::user()->company_branch_id)
                ->orderBy('date','DESC');
        }

        return DataTables::eloquent($query)
            ->addColumn('customer_name', function (PurchaseInventoryLog $purchase_inventory_log) {
                return $purchase_inventory_log->customer->name??'';
            })
            ->addColumn('date', function (PurchaseInventoryLog $purchase_inventory_log) {
                return $purchase_inventory_log->date->format('d-m-Y');
            })
            ->editColumn('selling_price', function (PurchaseInventoryLog $purchase_inventory_log) {
               if (auth()->user()->role == 2)
                    return $purchase_inventory_log->unit_price + nbrSellCalculation($purchase_inventory_log->unit_price);
               else
                   return $purchase_inventory_log->selling_price;
            })
            ->addColumn('action', function (PurchaseInventoryLog $purchase_inventory_log) {
                if (auth()->user()->role != 2){
                    return '<a class="btn btn-info btn-sm" href="' . route('sales_return.edit', ['purchase_inventory_log' => $purchase_inventory_log->id]) . '"> Edit </a>
                        <a class="btn btn-info btn-sm" href="' . route('sales_return.details', ['purchase_inventory_log' => $purchase_inventory_log->id]) . '"> Details </a>
                        <a role="button" class="btn btn-success btn-sm barcode_modal" data-id="' . $purchase_inventory_log->purchaseInventory->id . '" data-name="' . $purchase_inventory_log->productItem->name . '" data-code="' . $purchase_inventory_log->serial . '"> Barcode </a>';
            }

            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function returnInvoiceDelete(Request $request){
        $productReturnInvoice = ProductReturnOrder::where('id',$request->returnId)
            ->with('logs')
            ->first();

        //dd($productReturnInvoice->logs);
        foreach ($productReturnInvoice->logs as $log){
            $log->delete();
        }
        $productReturnInvoice->delete();

        return response()->json(['success' => true, 'message' => "Return Invoice Delete Successfully"]);
    }
    public function getSaleReturnDetails(Request $request){

        $products =SalesOrderProduct::with('model','size')->where('sales_order_id', $request->saleOrderId)->get()->toArray();

        $data = [
            'products' => $products,
        ];


        return response()->json($data);

    }
}
