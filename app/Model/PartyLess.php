<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PartyLess extends Model
{
    protected $guarded = [];

    public function customer() {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
