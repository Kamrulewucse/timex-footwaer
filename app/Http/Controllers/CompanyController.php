<?php

namespace App\Http\Controllers;

use App\Model\BranchCash;
use Illuminate\Http\Request;
use App\Model\Company;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index() {
         $companies = Company::all();

        return view('administrator.company.all', compact('companies'));
    }

    public function add() {
        return view('administrator.company.add');
    }

    public function addPost(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);
        try {
            DB::beginTransaction();

        $company = new Company();
        $company->name = $request->name;
        $company->status = $request->status;
        $company->save();

        DB::commit();

        return redirect()->route('company')->with('message', 'Company added successfully.');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->withInput()->with('error',$exception->getMessage());
        }

    }

    public function edit(Company $company) {
        return view('administrator.company.edit', compact('company'));
    }

    public function editPost(Company $company, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $company->name = $request->name;
        $company->status = $request->status;
        $company->save();

        return redirect()->route('company')->with('message', 'Company edited successfully.');
    }
}
