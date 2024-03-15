<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BalanceTransfer extends Model
{
    protected $fillable = [
        'type', 'source_bank_id', 'source_branch_id', 'source_bank_account_id',
        'source_cheque_no', 'source_cheque_image', 'target_bank_id', 'target_branch_id',
        'target_bank_account_id', 'target_cheque_no', 'target_cheque_image', 'amount',
        'date', 'note'
    ];

    public function sourchBranch(){
        return $this->belongsTo(CompanyBranch::class,'source_com_branch_id','id');
    }
    public function targetBranch(){
        return $this->belongsTo(CompanyBranch::class,'target_com_branch_id','id');
    }
    public function sourceBankAccount(){
        return $this->belongsTo(BankAccount::class,'source_bank_account_id','id');
    }
    public function targetBankAccount(){
        return $this->belongsTo(BankAccount::class,'target_bank_account_id','id');
    }
}
