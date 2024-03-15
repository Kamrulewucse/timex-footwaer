<?php

namespace App\Model;

use App\ReturnPaidLog;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function getDueAttribute() {
        $customer = Customer::find($this->id);
        $orders = SalesOrder::where('customer_id', $this->id)->get();
        $total = 0;
        foreach ($orders as $order) {
            if ($order->total == 0 || $order->return_amount > 0) {
                $total += ($order->sub_total-$order->discount);
            }else{
                $total += $order->total;
            }
        }
        $paid = SalePayment::where('customer_id', $this->id)->where('status', 2)->sum('amount');
        //$return_amount = PurchaseInventoryLog::where('customer_id', $this->id)->where('return_status', 1)->sum('sale_total');
        $one_return_amount = SalesOrder::where('customer_id', $this->id)->sum('return_amount');
        $adjustment_amount = SalesOrder::where('customer_id', $this->id)->sum('sale_adjustment');
        $two_return_amount = TransactionLog::where('customer_id', $this->id)->where('return_adjustment_amount',1)->sum('amount');
        $party_discount_amount = PartyLess::where('customer_id', $this->id)->sum('amount');
        $allReturnAmount = ($one_return_amount + $two_return_amount+$party_discount_amount+$adjustment_amount);
        return ($total - $paid) + ($customer->opening_due - $allReturnAmount);
    }

    public function getPaidAttribute() {
        return SalePayment::where('customer_id', $this->id)->where('status', 2)->sum('amount');
    }

    public function getTotalAttribute() {
        $orders = SalesOrder::where('customer_id', $this->id)->get();
        $total = 0;
        foreach ($orders as $order) {
                $total += $order->sub_total;
        }

        return $total;
    }
    public function getPartydiscountAttribute() {

        $party_discount_amount = PartyLess::where('customer_id', $this->id)->sum('amount');

        return $party_discount_amount;
    }
    public function getReturnAttribute() {

        return SalesOrder::where('customer_id', $this->id)->sum('return_amount');

    }
    public function getTransportAttribute() {
        return SalesOrder::where('customer_id', $this->id)->sum('transport_cost');

    }
    public function getDiscountAttribute() {
        return SalesOrder::where('customer_id', $this->id)->sum('discount');

    }

    public function getAdjustmentAttribute() {
        return SalesOrder::where('customer_id', $this->id)->sum('sale_adjustment');
    }

    public function getRefundAttribute() {
        return SalesOrder::where('customer_id', $this->id)->sum('refund');
    }
    public function branch(){
        return $this->belongsTo(CompanyBranch::class,'company_branch_id','id');
    }
//    public function partyLess(){
//        return $this->belongsTo(PartyLess::class,'customer_id','id');
//    }

    public function getQuantityAttribute() {
        $orders = SalesOrder::where('customer_id', $this->id)->pluck('id');
        $totalQuantity = SalesOrderProduct::whereIn('sales_order_id',$orders)->sum('quantity');
        return $totalQuantity;
    }

    public function getReturnAmountAttribute() {
        $orders = ProductReturnOrder::where('customer_id', $this->id)->where('status', 0)->get();

        $total = 0;
        foreach ($orders as $order) {
                $total += $order->total;
        }
        return $total;
    }
}
