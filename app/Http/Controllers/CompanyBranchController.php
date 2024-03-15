<?php

namespace App\Http\Controllers;

use App\Model\BranchCash;
use Illuminate\Http\Request;
use App\Model\CompanyBranch;
use Illuminate\Support\Facades\DB;

class CompanyBranchController extends Controller
{
    public function index() {
         $company_branch = CompanyBranch::all();

        return view('administrator.company_branch.all', compact('company_branch'));
    }

    public function add() {
        return view('administrator.company_branch.add');
    }

    public function addPost(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required'
        ]);
        try {
            DB::beginTransaction();

        $company_branch = new CompanyBranch();
        $company_branch->name = $request->name;
        $company_branch->address = $request->address;
        $company_branch->status = $request->status;
        $company_branch->save();

        $brancheCash= new BranchCash();
        $brancheCash->company_branch_id= $company_branch->id;
        $brancheCash->opening_balance = 0;
        $brancheCash->amount = 0;
        $brancheCash->save();

        DB::commit();
            return redirect()->route('company-branch')->with('message', 'Company Branch added successfully.');

        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->withInput()->with('error',$exception->getMessage());
        }

    }

    public function edit(CompanyBranch $company_branch) {
        return view('administrator.company_branch.edit', compact('company_branch'));
    }

    public function editPost(CompanyBranch $company_branch, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $company_branch->name = $request->name;
        $company_branch->status = $request->status;
        $company_branch->save();

        $brancheCash=BranchCash::where('company_branch_id',$company_branch->id)->first();
        if($brancheCash){
            $brancheCash->company_branch_id= $company_branch->id;
            $brancheCash->opening_balance = 0;
            $brancheCash->amount = 0;
            $brancheCash->save();
        }else{
            $brancheCash= new BranchCash();
            $brancheCash->company_branch_id= $company_branch->id;
            $brancheCash->opening_balance = 0;
            $brancheCash->amount = 0;
            $brancheCash->save();
        }

        return redirect()->route('company-branch')->with('message', 'Company Branch edited successfully.');
    }
}
