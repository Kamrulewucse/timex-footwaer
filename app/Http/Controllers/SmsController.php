<?php

namespace App\Http\Controllers;

use App\Model\CompanyBranch;
use App\Model\Customer;
use Illuminate\Http\Request;

class SmsController extends Controller{
    public function index(){
        $companyBranches = CompanyBranch::all();
        return view('sms_panel.all',compact('companyBranches'));
    }
    public function addPost(Request $request){
        //dd($request->all());
        $rules = [
            'message' => 'required|string|max:255'
        ];
        $request->validate($rules);
        if (auth()->user()->company_branch_id==0){
            $branchID = $request->branch;
            if ($branchID==0 && $request->customer==0){
                $customer_numbers = Customer::all()->pluck('mobile_no')->toArray();
            }elseif($branchID!=0 && $request->customer==0){
                $customer_numbers = Customer::where('company_branch_id',$request->branch)->pluck('mobile_no')->toArray();
                dd($customer_numbers);
            }else{
                if ($request->mobile_numbers && count($request->mobile_numbers)>0){
                    $customer_numbers = $request->mobile_numbers;
                }else{
                    return redirect()->back()->withInput()->with('error','Please select customer mobile Number!!!');
                }
            }
        }else{
            if ($request->customer==0){
                $customer_numbers = Customer::where('company_branch_id',auth()->user()->company_branch_id)->pluck('mobile_no')->toArray();
            }else{
                if ($request->mobile_numbers && count($request->mobile_numbers)>0){
                    $customer_numbers = $request->mobile_numbers;
                }else{
                    return redirect()->back()->withInput()->with('error','Please select customer mobile Number!!!');
                }
            }
        }
        dd($customer_numbers);
    }
}
