<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReturnOrder extends Model
{
    protected $guarded = [];
    protected $appends = [
        'quantity'
    ];

    public function logs() {
        return $this->hasMany(PurchaseInventoryLog::class);
    }
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    public function getQuantityAttribute(){
        return $this->hasMany(PurchaseInventoryLog::class)->sum('quantity');
    }

    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }
    public function companyBranch() {
        return $this->belongsTo(CompanyBranch::class,'company_branch_id', 'id');
    }
}
