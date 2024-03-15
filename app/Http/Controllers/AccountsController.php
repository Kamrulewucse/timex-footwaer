<?php

namespace App\Http\Controllers;

use App\Model\AccountHeadSubType;
use App\Model\AccountHeadType;
use App\Model\BalanceTransfer;
use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\BranchCash;
use App\Model\Cash;
use App\Model\CompanyBranch;
use App\Model\MobileBanking;
use App\Model\Transaction;
use App\Model\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use DataTables;

class AccountsController extends Controller
{
    public function accountHeadType() {

        if (Auth::user()->company_branch_id == 0) {
            $types = AccountHeadType::whereNotIn('id', [1, 2, 3, 4,209,210,233])->get();
        }else{
            $types = AccountHeadType::whereNotIn('id', [1, 2, 3, 4,209,210,233])
                ->where('company_branch_id', Auth::user()->company_branch_id)
                ->get();
        }


        return view('accounts.account_head_type.all', compact('types'));
    }

    public function accountHeadTypeAdd() {
        return view('accounts.account_head_type.add');
    }

    public function accountHeadTypeAddPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|min:1|max:2',
            'status' => 'required'
        ]);

        $type = new AccountHeadType();
        $type->name = $request->name;
        $type->company_branch_id = Auth::user()->company_branch_id;
        $type->transaction_type = $request->type;
        $type->status = $request->status;
        $type->save();

        return redirect()->route('account_head.type')->with('message', 'Account head type add successfully.');
    }

    public function accountHeadTypeEdit(AccountHeadType $type) {
        return view('accounts.account_head_type.edit', compact('type'));
    }

    public function accountHeadTypeEditPost(AccountHeadType $type, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|min:1|max:2',
            'status' => 'required'
        ]);

        $type->name = $request->name;
        $type->transaction_type = $request->type;
        $type->status = $request->status;
        $type->save();

        return redirect()->route('account_head.type')->with('message', 'Account head type edit successfully.');
    }

    public function accountHeadSubType() {
        //$subTypes = AccountHeadSubType::whereNotIn('id', [1, 2, 3, 4])->get();
        if (Auth::user()->company_branch_id == 0) {
            $subTypes = AccountHeadSubType::whereNotIn('id', [1, 2, 3, 4,18,19,20])->get();
        }else{
            $subTypes = AccountHeadSubType::whereNotIn('id', [1, 2, 3, 4,18,19,20])
                ->where('company_branch_id', Auth::user()->company_branch_id)
                ->get();
        }

        return view('accounts.account_head_sub_type.all', compact('subTypes'));
    }

    public function accountHeadSubTypeAdd() {
        return view('accounts.account_head_sub_type.add');
    }

    public function accountHeadSubTypeAddPost(Request $request) {
        $request->validate([
            'type' => 'required',
            'name' => 'required|string|max:255',
            'account_head_type' => 'required',
            'status' => 'required'
        ]);

        $subType = new AccountHeadSubType();
        $subType->account_head_type_id = $request->account_head_type;
        $subType->company_branch_id = Auth::user()->company_branch_id;
        $subType->name = $request->name;
        $subType->status = $request->status;
        $subType->save();

        return redirect()->route('account_head.sub_type')->with('message', 'Account head sub type add successfully.');
    }

    public function accountHeadSubTypeEdit(AccountHeadSubType $subType) {
        return view('accounts.account_head_sub_type.edit', compact('subType'));
    }

    public function accountHeadSubTypeEditPost(AccountHeadSubType $subType, Request $request) {
        $request->validate([
            'type' => 'required',
            'name' => 'required|string|max:255',
            'account_head_type' => 'required',
            'status' => 'required'
        ]);

        $subType->account_head_type_id = $request->account_head_type;
        $subType->name = $request->name;
        $subType->status = $request->status;
        $subType->save();

        return redirect()->route('account_head.sub_type')->with('message', 'Account head sub type edit successfully.');
    }

    public function transactionIndex() {


        return view('accounts.transaction.all');
    }

    public function transactionAdd() {
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('accounts.transaction.add', compact('banks'));
    }

    public function transactionAddPost(Request $request) {
        $messages = [
            'bank.required_if' => 'The bank field is required.',
            'branch.required_if' => 'The branch field is required.',
            'account.required_if' => 'The account field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'sale_type_status' => 'required|integer|min:1|max:2',
            'type' => 'required|integer|min:1|max:2',
            'account_head_type' => 'required',
            'account_head_sub_type' => 'nullable',
            'payment_type' => 'required|integer|min:1|max:3',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'bank' => 'required_if:payment_type,==,2',
            'branch' => 'required_if:payment_type,==,2',
            'account' => 'required_if:payment_type,==,2',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_image' => 'nullable|image',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validator->after(function ($validator) use ($request) {
            if ($request->type == 2) {
                if ($request->payment_type == 1) {
                    $cash = Cash::first();
                    if ($request->amount > $cash->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                } elseif ($request->payment_type == 3) {
                    $mobileBanking = MobileBanking::first();

                    if ($request->amount > $mobileBanking->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                } else {
                    $bankAccount = BankAccount::find($request->account);

                    if ($request->amount > $bankAccount->balance)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }

            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $image = null;
        if ($request->payment_type == 2) {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/transaction_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/transaction_cheque/'.$filename;
            }
        }

        $transaction = new Transaction();
        $transaction->transaction_type = $request->type;
        $transaction->company_branch_id = 1;
        $transaction->sale_type_status = $request->sale_type_status;
        $transaction->account_head_type_id = $request->account_head_type;
        $transaction->account_head_sub_type_id = $request->account_head_sub_type??'';
        $transaction->transaction_method = $request->payment_type;
        $transaction->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $transaction->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $transaction->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $transaction->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $transaction->cheque_image = $image;
        $transaction->amount = $request->amount;
        $transaction->date = $request->date;
        $transaction->note = $request->note;
        $transaction->save();

        if ($request->type == 1) {
            // Income
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->increment('amount', $request->amount);

            } elseif ($request->payment_type == 3) {
                // Mobile Banking
                MobileBanking::first()->increment('amount', $request->amount);
            } else {
                // Bank
                BankAccount::find($request->account)->increment('balance', $request->amount);
            }
        } else {
            // Expense
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->decrement('amount', $request->amount);
            } elseif ($request->payment_type == 3) {
                // Mobile Banking
                MobileBanking::first()->decrement('amount', $request->amount);
            } else {
                // Bank
                BankAccount::find($request->account)->decrement('balance', $request->amount);
            }
        }

        $accountHeadType = AccountHeadType::find($request->account_head_type);

        $log = new TransactionLog();
        $log->date = $request->date;
        $log->particular = $accountHeadType->name;
        $log->transaction_type = $request->type;
        $log->transaction_method = $request->payment_type;
        $log->company_branch_id = 1;
        $log->sale_type_status = $request->sale_type_status;
        $log->account_head_type_id = $request->account_head_type;
        $log->account_head_sub_type_id = $request->account_head_sub_type??'';
        $log->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $log->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $log->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $log->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $log->cheque_image = $image;
        $log->amount = $request->amount;
        $log->note = $request->note;
        $log->transaction_id = $transaction->id;
        $log->net_profit = $request->type == 1 ? 2 : 0;
        $log->save();

        return redirect()->route('transaction.details', ['transaction' => $transaction->id]);
    }

    public function transactionEditPost(Request $request) {
        $rules = [
            'id' => 'required',
            'type' => 'required',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        $transaction = Transaction::find($request->id);

        if ($transaction->transaction_method == 1) {
            $balance = Cash::first()->amount;

        } elseif ($transaction->transaction_method == 3) {
            $balance = MobileBanking::first()->amount;
        } else {
            $balance = BankAccount::find($transaction->bank_account_id)->balance;
        }

        if ($request->type == 1) {
            $updateBalance = ($balance - $transaction->amount) + $request->amount;
        } else {
            $updateBalance = ($balance + $transaction->amount) - $request->amount;
        }

        $validator->after(function ($validator) use ($updateBalance) {
            if ($updateBalance < 0) {
                $validator->errors()->add('amount', 'Insufficient balance.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $transaction->amount = $request->amount;
        $transaction->note = $request->note;
        $transaction->save();

        if ($transaction->transaction_method == 1) {
            Cash::first()->update([
                'amount' => $updateBalance
            ]);
        } elseif ($transaction->transaction_method == 3) {
            MobileBanking::first()->update([
                'amount' => $updateBalance
            ]);
        } else {
            $balance = BankAccount::find($transaction->bank_account_id)->update([
                'balance' => $updateBalance
            ]);
        }

        TransactionLog::where('transaction_id', $transaction->id)
            ->update([
                'amount' => $request->amount,
                'note' => $request->note
            ]);

        return response()->json(['success' => true, 'message' => 'Transaction has been updated.']);
    }

    public function transactionDetails(Transaction $transaction) {
        $transaction->amount_in_word = DecimalToWords::convert($transaction->amount,'Taka',
            'Poisa');

        return view('accounts.transaction.details', compact('transaction'));
    }

    public function transactionDetailsJson(Request $request) {
        $transaction = Transaction::find($request->transactionId)->toArray();

        return response()->json($transaction);
    }

    public function transactionPrint(Transaction $transaction) {
        $transaction->amount_in_word = DecimalToWords::convert($transaction->amount,'Taka',
            'Poisa');

        return view('accounts.transaction.print', compact('transaction'));
    }

    public function balanceTransferAdd() {
        $bankAccounts = BankAccount::where('status', 1)
            ->orderBy('account_no')
            ->get();
        if (auth()->user()->company_branch_id == 0){
            $companyBranches = CompanyBranch::orderBy('name')->get();
        }else{
            $companyBranches = CompanyBranch::where('id',auth()->user()->company_branch_id)->orderBy('name')->get();
        }
        $targetBranches = CompanyBranch::orderBy('name')->get();
        return view('accounts.balance_transfer.add', compact('bankAccounts','companyBranches','targetBranches'));
    }

    public function balanceTransferAddPost(Request $request) {
        //return($request->all());
        $messages = [
            'source_bank.required_if' => 'The source bank field is required.',
            'source_branch.required_if' => 'The source branch field is required.',
            'source_account.required_if' => 'The source account field is required.',
            'target_bank.required_if' => 'The target bank field is required.',
            'target_branch.required_if' => 'The target branch field is required.',
            'target_account.required_if' => 'The target account field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|min:1|max:4',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'source_account' => 'required_if:type,==,1|required_if:type,==,3',
            'source_cheque_no' => 'nullable|string|max:255',
            'source_cheque_image' => 'nullable|image',
            'target_account' => 'required_if:type,==,2|required_if:type,==,3',
            'target_cheque_no' => 'nullable|string|max:255',
            'target_cheque_image' => 'nullable|image',
        ], $messages);

        if($request->source_branch == $request->target_branch){
            return redirect()->back()->withInput()->with('error', 'Source Branch and Target Branch Cant Not Be The Same !');
        }
        if($request->type == 3){
            if($request->source_account == $request->target_account){
                return redirect()->back()->withInput()->with('error', 'Source Account and Target Account Cant Not Be The Same !');
            }
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validator->after(function ($validator) use ($request) {
            if ($request->type == 1 || $request->type == 3) {
                $bankAccount = BankAccount::find($request->source_account);
                if ($request->amount > $bankAccount->balance)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            }

            if($request->source_branch ==0){
                $cash = Cash::first();
                if ($request->amount > $cash->amount)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            }else{
                $cash = BranchCash::where('company_branch_id',$request->source_branch)->first();
                if ($request->amount > $cash->amount)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            }



        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sourceImage = null;
        $targetImage = null;
        if ($request->type == 1 || $request->type == 3) {
            $sourceImage = 'img/no_image.png';

            if ($request->source_cheque_image) {
                // Upload Image
                $file = $request->file('source_cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/balance_transfer_cheque';
                $file->move($destinationPath, $filename);

                $sourceImage = 'uploads/balance_transfer_cheque/'.$filename;
            }
        }

        if ($request->type == 2 || $request->type == 3) {
            $targetImage = 'img/no_image.png';

            if ($request->target_cheque_image) {
                // Upload Image
                $file = $request->file('target_cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/balance_transfer_cheque';
                $file->move($destinationPath, $filename);

                $targetImage = 'uploads/balance_transfer_cheque/'.$filename;
            }
        }

        $sourceAccount = BankAccount::find($request->source_account);
        $targetAccount = BankAccount::find($request->target_account);

        $transfer = new BalanceTransfer();
        $transfer->type = $request->type;
        $transfer->source_com_branch_id = $request->source_branch;
        $transfer->target_com_branch_id = $request->target_branch;
        $transfer->source_bank_id = in_array($request->type, [1, 3]) ? $sourceAccount->bank_id : null;
        $transfer->source_branch_id = in_array($request->type, [1, 3]) ? $sourceAccount->branch_id : null;
        $transfer->source_bank_account_id = in_array($request->type, [1, 3]) ? $sourceAccount->id : null;
        $transfer->source_cheque_no = in_array($request->type, [1, 3]) ? $request->source_cheque_no : null;
        $transfer->source_cheque_image = $sourceImage;
        $transfer->target_bank_id = in_array($request->type, [2, 3]) ? $targetAccount->bank_id : null;
        $transfer->target_branch_id = in_array($request->type, [2, 3]) ? $targetAccount->branch_id : null;
        $transfer->target_bank_account_id = in_array($request->type, [2, 3]) ? $targetAccount->id : null;
        $transfer->target_cheque_no = in_array($request->type, [2, 3]) ? $request->target_cheque_no : null;
        $transfer->target_cheque_image = $targetImage;
        $transfer->amount = $request->amount;
        $transfer->date = $request->date;
        $transfer->note = $request->note;
        $transfer->save();

        if ($request->type == 1) {
            // Bank To Cash
            BankAccount::find($request->source_account)->decrement('balance', $request->amount);

            if($request->target_branch == 0){
                Cash::first()->increment('amount', $request->amount);
            }else{
                $branchCash=BranchCash::where('company_branch_id',$request->target_branch)->first();
                $branchCash->amount = $branchCash->amount+$request->amount;
                $branchCash->save();

            }

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->company_branch_id = $request->source_branch;
            $log->transaction_method = 2;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->bank_id = $sourceAccount->bank_id;
            $log->branch_id = $sourceAccount->branch_id;
            $log->bank_account_id = $sourceAccount->id;
            $log->cheque_no = $request->source_cheque_no;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->source_com_branch_id = $request->source_branch;
            $log->target_com_branch_id = $request->target_branch;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->company_branch_id = $request->target_branch;
            $log->transaction_method = 1;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->source_com_branch_id = $request->source_branch;
            $log->target_com_branch_id = $request->target_branch;
            $log->save();
        } elseif ($request->type == 2) {
            // Cash To Bank
            if($request->source_branch ==0){
                Cash::first()->decrement('amount', $request->amount);
            }else{
                $branchCash=BranchCash::where('company_branch_id',$request->source_branch)->first();
                $branchCash->amount = $branchCash->amount-$request->amount;
                $branchCash->save();
            }
            BankAccount::find($request->target_account)->increment('balance', $request->amount);


            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->company_branch_id = $request->source_branch;
            $log->transaction_method = 1;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->source_com_branch_id = $request->source_branch;
            $log->target_com_branch_id = $request->target_branch;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->company_branch_id = $request->target_branch;
            $log->transaction_method = 2;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->bank_id = $targetAccount->bank_id;
            $log->branch_id = $targetAccount->branch_id;
            $log->bank_account_id = $targetAccount->id;
            $log->cheque_no = $request->target_cheque_no;
            $log->cheque_image = $targetImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->source_com_branch_id = $request->source_branch;
            $log->target_com_branch_id = $request->target_branch;
            $log->save();
        } elseif ($request->type == 4) {
            // Cash To Cash
            if($request->source_branch ==0){
                Cash::first()->decrement('amount', $request->amount);
            }else{
                $branchCash=BranchCash::where('company_branch_id',$request->source_branch)->first();
                $branchCash->amount = $branchCash->amount-$request->amount;
                $branchCash->save();
            }
            if($request->target_branch ==0){
                Cash::first()->increment('amount', $request->amount);
            }else{
                $branchCash=BranchCash::where('company_branch_id',$request->target_branch)->first();
                $branchCash->amount = $branchCash->amount+$request->amount;
                $branchCash->save();
            }

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->company_branch_id = $request->source_branch;
            $log->transaction_method = 1;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->source_com_branch_id = $request->source_branch;
            $log->target_com_branch_id = $request->target_branch;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->company_branch_id = $request->target_branch;
            $log->transaction_method = 1;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->source_com_branch_id = $request->source_branch;
            $log->target_com_branch_id = $request->target_branch;
            $log->save();
        }else {
            // Bank To Bank
            BankAccount::find($request->source_account)->decrement('balance', $request->amount);
            BankAccount::find($request->target_account)->increment('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->company_branch_id = $request->source_branch;
            $log->transaction_method = 2;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->bank_id = $sourceAccount->bank_id;
            $log->branch_id = $sourceAccount->branch_id;
            $log->bank_account_id = $sourceAccount->id;
            $log->cheque_no = $request->source_cheque_no;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->source_com_branch_id = $request->source_branch;
            $log->target_com_branch_id = $request->target_branch;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->company_branch_id = $request->target_branch;
            $log->transaction_method = 2;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->bank_id = $targetAccount->bank_id;
            $log->branch_id = $targetAccount->branch_id;
            $log->bank_account_id = $targetAccount->id;
            $log->cheque_no = $request->target_cheque_no;
            $log->cheque_image = $targetImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->source_com_branch_id = $request->source_branch;
            $log->target_com_branch_id = $request->target_branch;
            $log->save();
        }

        return redirect()->route('balance_transfer.add')->with('message', 'Balance transfer successful.');
    }

    public function transactionDatatable() {
        $query = Transaction::with('accountHeadType', 'accountHeadSubType');

        return DataTables::eloquent($query)
            ->addColumn('accountHeadType', function(Transaction $transaction) {
                return $transaction->accountHeadType->name;
            })
            ->addColumn('accountHeadSubType', function(Transaction $transaction) {
                return $transaction->accountHeadSubType->name??'';
            })
            ->addColumn('action', function(Transaction $transaction) {
                return '<a href="'.route('transaction.details', ['transaction' => $transaction->id]).'" class="btn btn-primary btn-sm">Details</a> <a role="button" data-id="'.$transaction->id.'" class="btn btn-info btn-sm btn-edit">Edit</a>';
            })
            ->editColumn('date', function(Transaction $transaction) {
                return $transaction->date->format('j F, Y');
            })
            ->editColumn('transaction_type', function(Transaction $transaction) {
                if ($transaction->transaction_type == 1)
                    return '<span class="badge badge-success">Income</span>';
                else
                    return '<span class="badge badge-warning">Expense</span>';
            })
            ->editColumn('amount', function(Transaction $transaction) {
                return 'Tk '.number_format($transaction->amount, 2);
            })
            ->orderColumn('date', function ($query, $transaction) {
                $query->orderBy('date', $transaction)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action', 'transaction_type'])
            ->toJson();
    }
}
