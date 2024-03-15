<?php

namespace App\Http\Controllers;

use App\Model\CompanyBranch;
use App\Model\Customer;
use App\Model\SalesOrder;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index() {
        return view('sale.customer.all');
    }

    public function add() {
        $branches = CompanyBranch::where('status', 1)->orderBy('name')->get();
        return view('sale.customer.add',compact('branches'));
    }

    public function addPost(Request $request) {
        $rules = [
            'name' => 'required|string|max:255',
            'mobile_no' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'opening_due' => 'required|numeric',
        ];
        if (Auth::user()->company_branch_id==0){
            $rules['branch'] = 'required|numeric';
        }
        $request->validate($rules);

        $id_no = Customer::max('id_no');
        if (!$id_no){
            $id_no = 1000;
        }else{
            $id_no += 1;
        }
        if ($request->type == 'retail_sale'){
            $type = 1;
        }elseif ($request->type == 'retail_sale'){
            $type = 2;
        }
        $company_branch_id = $request->branch;
        $checkCustomer = Customer::where('type',$type)->where('company_branch_id',$company_branch_id)->where('name',$request->name)->first();
        if ($checkCustomer){
            return redirect()->back()->withInput()->with('error','Customer already exist in this Branch');
        }

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->id_no = $id_no;
        $customer->type = $type;
        $customer->company_branch_id = $company_branch_id;
        $customer->mobile_no = $request->mobile_no;
        $customer->address = $request->address;
        $customer->opening_due = $request->opening_due;
        $customer->status = $request->status;
        $customer->save();

        return redirect()->route('customer',['type'=>$request->type])->with('message', 'Customer add successfully.');
    }

    public function addAjaxPost(Request $request){
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'opening_due' => 'required|numeric',
        ];
        if (Auth::user()->company_branch_id==0){
            $rules['branch'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $company_branch_id = $request->branch;
        $checkCustomer = Customer::where('company_branch_id',$company_branch_id)->where('name',$request->name)->first();
        if ($checkCustomer){
            return response()->json(['success' => false, 'message' => 'Customer already exist in this Branch']);
        }
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->company_branch_id = $company_branch_id;
        $customer->mobile_no = $request->phone;
        $customer->address = $request->address;
        $customer->opening_due = $request->opening_due;
        $customer->status = 1;
        $customer->save();
        return response()->json(['success' => true, 'message' => 'added','customer'=>$customer]);
    }

    public function edit(Customer $customer) {
        $branches = CompanyBranch::where('status', 1)->orderBy('name')->get();
        return view('sale.customer.edit', compact('customer','branches'));
    }

    public function editPost(Customer $customer, Request $request) {
        $rules=[
            'name' => 'required|string|max:255',
            'mobile_no' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'opening_due' => 'required|numeric',
        ];
        if (Auth::user()->company_branch_id==0){
            $rules['branch'] = 'required|numeric';
        }
        $request->validate($rules);
        $customer->name = $request->name;
        $customer->mobile_no = $request->mobile_no;
        $customer->address = $request->address;
        $customer->opening_due = $request->opening_due;
        $customer->status = $request->status;
        $customer->save();

        return redirect()->route('customer',['type'=>$request->type])->with('message', 'Customer edit successfully.');
    }

    public function datatable() {
        if (\request()->get('type') == 'retail_sale'){
           $type = 1;
        }elseif (\request()->get('type') == 'whole_sale'){
            $type = 2;
        }
        $query = Customer::with('branch')->where('type',$type);
        return DataTables::eloquent($query)
            ->addColumn('action', function(Customer $customer) {
                return '<a class="btn btn-info btn-sm" href="'.route('customer.edit', ['customer' => $customer->id,'type'=>\request()->get('type')]).'"> Edit';
            })
            ->addColumn('branch', function(Customer $customer) {
                return $customer->branch->name??'';
            })
            ->addColumn('status', function(Customer $customer) {
                if ($customer->status == 1) {
                    return '<span class="badge badge-success">Active</span>';
                }else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('branch', function(Customer $customer) {
                return $customer->branch->name??'';
            })
            ->rawColumns(['action','status','branch_status'])
            ->toJson();
    }
    public function customerPreviousReceipt(Request $request){
        $order_receipts = SalesOrder::where('customer_id',$request->customer_id)->orderBy('id','desc')->take(10)->get();
        if (count($order_receipts) > 0){
            return response()->json(['success' => true,'order_receipts'=>$order_receipts]);
        }
        return response()->json(['success' => false,'order_receipts'=>[]]);
    }
    public function customerNumberSuggestion(Request $request){
        if ($request->term){
            $query = Customer::query();
            $query->where('name', 'like', '%' . $request->term . '%');
            $query->orWhere('mobile_no', 'like', '%' . $request->term . '%');
            if (Auth::user()->company_branch_id != 0){
                $query->where('company_branch_id',Auth::user()->company_branch_id);
            }
            if ($request->branchId != 0){
                $query->where('company_branch_id',$request->branchId);
            }
            $query->take(12);
            $customers = $query->get();
            return response()->json(['success'=>true,'customers'=>$customers]);
        }
    }
}
