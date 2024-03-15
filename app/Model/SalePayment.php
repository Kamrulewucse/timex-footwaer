<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalePayment extends Model
{

    //use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['date'];

    public function salesOrder() {
        return $this->belongsTo(SalesOrder::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function subCustomer(){
        return $this->belongsTo(SubCustomer::class);
    }

    public function bank() {
        return $this->belongsTo(Bank::class,'bank_id','id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function account() {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }
    public function companyBranch() {
        return $this->belongsTo(CompanyBranch::class, 'company_branch_id', 'id');
    }
    public function transactionLog() {
        return $this->hasOne(TransactionLog::class,'sale_payment_id');
    }
}
