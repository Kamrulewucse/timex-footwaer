<?php

use App\Model\SalePayment;

function enNumberToBn($number)
{
    $enDigits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $bnDigits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

// Convert English digits to Bengali digits
    return strtr($number, array_combine($enDigits, $bnDigits));

}

function bnNumberToEn($number)
{
    $bnDigits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    $enDigits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');


    return strtr($number, array_combine($bnDigits, $enDigits));
    }

if (! function_exists('nbrCalculation')) {
    function nbrCalculation(){
        if (auth()->user()->role == 1){
            return 1;
        }else{
            return .35;
        }
    }
}
if (! function_exists('todayPendingCheque')) {
    function todayPendingCheque(){
        return SalePayment::whereDate('cheque_date',date('Y-m-d'))->where('status', 1)->count();
    }
}
if (! function_exists('todayPendingCach')) {
    function todayPendingCach(){
        return SalePayment::where('transaction_method',1)->whereDate('date',date('Y-m-d'))->where('status', 1)->count();
    }
}
//if (! function_exists('nbrSellCalculation')) {
//    function nbrSellCalculation($amount = 0){
//        if (auth()->user()->role == 1){
//            return 0;
//        }else{
//            return (10 / 100) * $amount;
//        }
//    }
//}
if (! function_exists('getSalePriceInventoryLog')) {
    function getSalePriceInventoryLog($orderId,$productItemId){

        return \App\Model\PurchaseInventoryLog::where('sales_order_id',$orderId)
            ->where('product_item_id',$productItemId)->first();

    }
}
if (! function_exists('getSaleReceiptTotal')) {
    function getSaleReceiptTotal($order){
        $totalAmount = 0;
        foreach($order->products as $key => $item){
            if(auth()->user()->role == 2){
                $totalAmount = ((($item->buy_price) * $item->quantity) + $order->transport_cost + $order->vat - $order->discount - $order->return_amount) + $order->paid;
            } else{
                $totalAmount = $order->total;

            }
        }
        return $totalAmount;
    }
}


