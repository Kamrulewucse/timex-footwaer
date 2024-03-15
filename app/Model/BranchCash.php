<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BranchCash extends Model
{
    protected $fillable = [];

    public function companyBranch() {
        return $this->belongsTo(CompanyBranch::class,'company_branch_id','id');
    }
}
