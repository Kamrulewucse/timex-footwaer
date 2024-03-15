<?php

namespace App\Http\Controllers;

use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Branch;
use App\Model\BranchCash;
use App\Model\Cash;
use App\Model\CompanyBranch;
use App\Model\MobileBanking;
use App\Model\Transaction;
use App\Model\TransactionLog;
use App\model\WithdrawLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index() {
            $accounts = BankAccount::with('bank', 'branch')->get();
        return view('bank_n_account.account.all', compact('accounts'));
    }

    public function add() {
        $banks = Bank::orderBy('name')->get();
        return view('bank_n_account.account.add', compact('banks'));
    }

    public function addPost(Request $request) {
//        return($request->all());
        $request->validate([
            'bank' => 'required',
            'branch' => 'required',
            'account_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:255',
            'account_code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'opening_balance' => 'required|numeric|min:0',
            'status' => 'required'
        ]);

        $account = new BankAccount();
        $account->bank_id = $request->bank;
        $account->branch_id = $request->branch;
        $account->account_name = $request->account_name;
        $account->account_no = $request->account_no;
        $account->description = $request->description;
        $account->opening_balance = $request->opening_balance;
        $account->balance = $request->opening_balance;
        $account->status = $request->status;
        $account->save();

        return redirect()->route('bank_account')->with('message', 'Bank account add successfully.');
    }

    public function edit(BankAccount $account) {
        $banks = Bank::orderBy('name')->get();
        return view('bank_n_account.account.edit', compact('banks', 'account'));
    }

    public function editPost(BankAccount $account, Request $request) {
        $request->validate([
            'bank' => 'required',
            'branch' => 'required',
            'account_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:255',
            'account_code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $account->bank_id = $request->bank;
        $account->branch_id = $request->branch;
        $account->account_name = $request->account_name;
        $account->account_no = $request->account_no;
        $account->description = $request->description;
        $account->status = $request->status;
        $account->save();

        return redirect()->route('bank_account')->with('message', 'Bank account edit successfully.');
    }

    public function getBranches(Request $request) {
        $branches = Branch::where('bank_id', $request->bankId)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($branches);
    }

    public function bankAccountDetailsJson(Request $request) {
        $bankAccount = BankAccount::find($request->accountId)->toArray();

        return response()->json($bankAccount);
    }

    public function bankAmountWithdrawPost(Request $request) {
            $rules = [
                'id' => 'required',
                'amount' => 'required|numeric|min:0',
                'note' => 'nullable|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);


            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }


            $bankAccount = BankAccount::find($request->id);
            if($bankAccount->balance < $request->amount){
                return response()->json(['success' => false, 'message' => 'Insufficient balance.']);
            }

//        $bankAccount->balance =  $bankAccount->balance - $request->amount;
//        $bankAccount->save();

        $withdrawLog = new WithdrawLog();
        $withdrawLog->bank_account_id = $request->id;
        $withdrawLog->amount = $request->amount;
        $withdrawLog->note = $request->note;
        $withdrawLog->date = $request->date;
        $withdrawLog->user_id = auth()->user()->id;
        $withdrawLog->save();

        return response()->json(['success' => true, 'message' => 'Withdraw has been completed.']);
        }
}
