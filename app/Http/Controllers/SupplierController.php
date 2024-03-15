<?php

namespace App\Http\Controllers;

use App\Model\Supplier;
use App\Model\PurchasePayment;
use App\Model\TransactionLog;
use App\Model\BankAccount;
use App\Model\Cash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::all();

        return view('purchase.supplier.all', compact('suppliers'));
    }

    public function add() {
        return view('purchase.supplier.add');
    }

    public function addPost(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers',
            'owner_name' => 'nullable|string|max:255',
            'mobile_no' => 'required|digits:11',
            'alternative_mobile_no' => 'nullable|digits:11',
            'email' => 'nullable|email|string|max:255',
            'address' => 'required|string|max:255',
            'opening_due' => 'required|numeric',
            'status' => 'required'
        ]);

        $id_no = Supplier::max('id_no');
        if (!$id_no){
            $id_no = 1000;
        }else{
            $id_no += 1;
        }
        $supplier = new Supplier();
        $supplier->id_no = $id_no;
        $supplier->name = $request->name;
        $supplier->owner_name = $request->owner_name;
        $supplier->mobile = $request->mobile_no;
        $supplier->alternative_mobile = $request->alternative_mobile_no;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->opening_due = $request->opening_due;
        $supplier->status = $request->status;
        $supplier->save();

        return redirect()->route('supplier')->with('message', 'Supplier add successfully.');
    }
    public function addAjaxPost(Request $request){
        $rules = [
            'name' => 'required|string|max:255|unique:suppliers',
            'owner_name' => 'nullable|string|max:255',
            'phone' => 'required|digits:11',
            'alternative_mobile_no' => 'nullable|digits:11',
            'email' => 'nullable|email|string|max:255',
            'address' => 'required|string|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->mobile = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->opening_due = 0;
        $supplier->status = 1;
        $supplier->save();
        return response()->json(['success' => true, 'message' => 'added','supplier'=>$supplier]);
    }
    public function edit(Supplier $supplier) {
        return view('purchase.supplier.edit', compact('supplier'));
    }

    public function editPost(Supplier $supplier, Request $request) {
        $request->validate([
//            'name' => 'required|string|max:255|unique:suppliers,name'.$supplier->id,
            'name' => ['required','string','max:255',Rule::unique('suppliers')->ignore($supplier)],
            'owner_name' => 'nullable|string|max:255',
            'mobile_no' => 'required|digits:11',
            'alternative_mobile_no' => 'nullable|digits:11',
            'email' => 'nullable|email|string|max:255',
            'address' => 'required|string|max:255',
            'opening_due' => 'required|numeric',
            'status' => 'required'
        ]);

        $supplier->name = $request->name;
        $supplier->owner_name = $request->owner_name;
        $supplier->mobile = $request->mobile_no;
        $supplier->alternative_mobile = $request->alternative_mobile_no;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->opening_due = $request->opening_due;
        $supplier->status = $request->status;
        $supplier->save();

        return redirect()->route('supplier')->with('message', 'Supplier edit successfully.');
    }

    public function voucherUpdate(Request $request) {
        // $supplier = Supplier::where('id', $request->id)->first();
        // dd($supplier);
        $rules = [
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $payment = PurchasePayment::where('id',$request->payment_id)->first();

//        dd($payment);
        if ($payment){
            if ($payment->transaction_method == 1) {
                Cash::first()->decrement('amount', $payment->amount);
                Cash::first()->increment('amount', $request->amount);
            }else{
                BankAccount::find($payment->bank_account_id)->decrement('balance', $payment->amount);
                BankAccount::find($payment->bank_account_id)->increment('balance', $request->amount);
            }
        }

        $payment->amount = $request->amount;
        $payment->note = $request->note;
        $payment->supplier_id = Auth::user()->id;
        $payment->save();

        $log = TransactionLog::where('purchase_payment_id',$request->payment_id)->first();

        $log->amount = $request->amount;
        $log->company_branch_id = Auth::user()->company_branch_id;
        $log->note = $request->note;
        $log->supplier_id = $payment->supplier_id;
        $log->save();

        return response()->json(['success' => true, 'message' => 'Payment has been Updated.', 'redirect_url' => route('purchase_receipt.payment_details', ['payment' => $payment->id])]);
    }

    public function supplierVoucherDelete(Request $request){
        $purchasePayment = PurchasePayment::where('id',$request->id)->first();
        if ($purchasePayment) {
            if ($purchasePayment->transaction_method == 1) {
                Cash::first()->decrement('amount', $purchasePayment->amount);
            }else{
                BankAccount::find($purchasePayment->bank_account_id)->decrement('balance', $purchasePayment->amount);
            }
            TransactionLog::where('purchase_payment_id',$purchasePayment->id)->delete();

            $purchasePayment->delete();

        }
        return redirect(route('supplier_payments', ['supplier'=>$purchasePayment->supplier_id]))->with('message','Supplier Payment Delete Successfully');
    }
}
