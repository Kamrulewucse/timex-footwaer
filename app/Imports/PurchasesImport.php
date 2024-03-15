<?php

namespace App\Imports;

use App\Model\BankAccount;
use App\Model\Cash;
use App\Model\ProductCategory;
use App\Model\ProductItem;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseOrder;
use App\Model\PurchaseOrderProduct;
use App\Model\PurchasePayment;
use App\Model\Supplier;
use App\Model\TransactionLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Ramsey\Uuid\Uuid;

class PurchasesImport implements ToCollection
{
    /**
     * @param $fileName
     */

    public function __construct($fileName){
        $this->fileName = $fileName;
    }

    public function collection(Collection $collection){
        try {
            $sub_total = 0;
            foreach ($collection as $key => $data) {

                if($key== 0){
                    continue;
                }
                if($key == 1){
                    //$paidAmount = trim($data[6]);
                    $paidAmount = 0;
//                    $bankAccountNo = $data[7];
                    $supplier = Supplier::where('name',trim($data[0]))->first();
                    if(!$supplier){
                        $supplier = new Supplier();
                        $supplier->name = trim($data[0]);
                        $supplier->save();
                    }
                    $order = new PurchaseOrder();
                    $order->order_no = rand(10000000, 99999999);
                    $order->supplier_id = $supplier->id;
                    $order->warehouse_id = 1;
                    $order->product_type =2;
                    $order->transport_cost = 0;
                    $order->discount_percentage = 0;
                    $order->user_id = Auth::id();
                    $order->discount = 0;
                    $order->date = date('Y-m-d');
                    $order->total = 0;
                    $order->paid = 0;
                    $order->due = 0;
                    $order->save();
                    $order->order_no = str_pad($order->id, 5, 0, STR_PAD_LEFT);
                    $order->save();
                }

                if (!$data[1]){
                    break;
                }

                $productItem = ProductItem::where('name',trim( $data[1]))->first();
                if(!$productItem){
                    $productItem = new ProductItem();
                    $productItem->name = trim($data[1]);
                    $productItem->supplier_id = $supplier->id;
                    $productItem->save();
                }
                $product_category = ProductCategory::where('name', trim($data[2]))->first();
                if(!$product_category){
                    $product_category = new ProductCategory();
                    $product_category->name = trim($data[2]);
                    $product_category->save();
                }

                $purchase_order_product = PurchaseOrderProduct::where('purchase_order_id', $order->id)
                    ->where('product_item_id', $productItem->id)
                    ->where('product_category_id', $product_category->id)
                    ->where('warehouse_id', 1)
                    ->first();

                if (empty($purchase_order_product)) {
                    $purchase_order_product = PurchaseOrderProduct::create([
                        'purchase_order_id' => $order->id,
                        'product_item_id' => $productItem->id,
                        'product_category_id' => $product_category->id,
                        'warehouse_id' => 1,
                        'product_type' => 2,
                        'date' => date('Y-m-d'),
                        'quantity' => $data[3],
                        'unit_price' => $data[4],
                        'selling_price' => $data[5],
                        'wholesale_price' => $data[6],
                        'total' => $data[3] * $data[4],
                    ]);
                    $sub_total += $data[3] * $data[4];

                    // Inventory Log
                    $log = PurchaseInventoryLog::create([
                        'purchase_order_id' => $order->id,
                        'product_item_id' => $productItem->id,
                        'product_category_id' => $product_category->id,
                        'warehouse_id' => 1,
                        'supplier_id' => $supplier->id,
                        'type' => 1,
                        'date' => date('Y-m-d'),
                        'quantity' => $data[3],
                        'unit_price' => $data[4],
                        'selling_price' => $data[5],
                        'wholesale_price' => $data[6],
                        'sale_total' => $data[3] * $data[5],
                        'total' => $data[3] * $data[4],
                        'note' => 'Purchase Product',
                        'user_id' => Auth::id(),
                    ]);

                    $inventory = PurchaseInventory::where('product_item_id', $productItem->id)
                        ->where('product_category_id', $product_category->id)
                        ->where('warehouse_id', 1)
                        ->first();


                    if ($inventory) {
                        $inventory->update([
                            'product_item_id' => $productItem->id,
                            'product_category_id' => $product_category->id,
                            'warehouse_id' => 1,
                            'quantity' => $inventory->quantity + $data[3],
                            'unit_price' => $data[4],
                            'selling_price' => $data[5],
                            'wholesale_price' => $data[6],
                            'total' => $data[3] * $data[4],
                        ]);
                    } else {
                        $inventory = PurchaseInventory::create([
                            'product_item_id' => $productItem->id,
                            'product_category_id' => $product_category->id,
                            'warehouse_id' => 1,
                            'quantity' => $data[3],
                            'unit_price' => $data[4],
                            'selling_price' => $data[5],
                            'wholesale_price' => $data[6],
                            'total' => $data[3] * $data[4],
                        ]);
                    }

                    $log->update([
                        'purchase_inventory_id' => $inventory->id,
                        'serial' => str_pad($inventory->id, 7, 0, STR_PAD_LEFT),
                    ]);

                    $purchase_order_product->update(['purchase_inventory_id' => $inventory->id]);
                    $totalAmount = PurchaseOrderProduct::where('purchase_inventory_id', $inventory->id)->sum('unit_price');
                    $totalQuantity = PurchaseOrderProduct::where('purchase_inventory_id', $inventory->id)->count();
                    $inventory->update([
                        'quantity' => $inventory->in_product - $inventory->out_product,
                        'serial' => str_pad($inventory->id, 7, 0, STR_PAD_LEFT),
                        'avg_unit_price' => $totalAmount / $totalQuantity,
                    ]);
                    $purchase_order_product->update(['serial' => $inventory->serial]);
                }

            }

            $cash=Cash::first();

            if($cash->amount<$paidAmount){
                throw new \Exception("Cash amount less then paid amount !");
            }


            $order->total = $sub_total;
            $order->due = $sub_total;
            $order->save();


            if ($paidAmount > 0) {
//                if (!$bankAccount) {
                    $payment = new PurchasePayment();
                    $payment->purchase_order_id = $order->id;
                    $payment->supplier_id = $supplier->id;
                    $payment->transaction_method = 1;
                    $payment->amount = $paidAmount;
                    $payment->date = date('Y-m-d');
                    $payment->note = null;
                    $payment->save();

                    //Cash decrement
                    $cash->decrement('amount',$paidAmount);

                    $log = new TransactionLog();
                    $log->date = date('Y-m-d');
                    $log->particular = 'Paid to ' . $order->supplier->name . ' for ' . $order->order_no;
                    $log->transaction_type = 3;
                    $log->transaction_method = 1;
                    $log->account_head_type_id = 1;
                    $log->account_head_sub_type_id = 1;
                    $log->amount = $paidAmount;
                    $log->note = null;
                    $log->purchase_payment_id = $payment->id;
                    $log->save();


                $order->increment('paid', $paidAmount);
                $order->decrement('due', $paidAmount);
            }

        }catch (\Exception $e){
            \Log::error($e->getMessage());

            throw new \Exception("Error occurred during import: " . $e->getMessage());

        }
    }
}
