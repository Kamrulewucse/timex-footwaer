<?php

namespace App\Http\View\Composers;

use App\Model\BalanceTransfer;
use App\Model\PurchaseInventory;
use App\Model\SalesOrder;
use Illuminate\View\View;
use DB;
use Illuminate\Support\Carbon;

class LayoutComposer
{
    public function __construct(){}
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $tomorrow = Carbon::tomorrow();
        $today = Carbon::today();

        if(auth()->user()->company_branch_id !=0){
            $chequePaymentWarnings = SalesOrder::where('company_branch_id',auth()->user()->company_branch_id)->whereDate('cheque_date', $tomorrow)->get();
            $balanceTransfers = BalanceTransfer::where('source_com_branch_id',auth()->user()->company_branch_id)->where('date', $today)->get();
        }else{
            $chequePaymentWarnings = SalesOrder::whereDate('cheque_date', $tomorrow)->get();
            $balanceTransfers = BalanceTransfer::where('date',$today)->get();
        }



        $chequePaymentWarningCount = $chequePaymentWarnings->count();

        $balanceTransferCount = $balanceTransfers->count();




        $notificationCount = $chequePaymentWarningCount+$balanceTransferCount;



        $data = [
            'chequePaymentWarnings' => $chequePaymentWarnings,
            'balanceTransfers' => $balanceTransfers,
            'notificationCount' => $notificationCount,
        ];

        $view->with('layoutData', $data);
    }
}
