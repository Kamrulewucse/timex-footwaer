<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    //use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['date','created_at'];

    public function products() {
        return $this->hasMany(PurchaseOrderProduct::class);
    }
    public function order_products() {
        return $this->hasMany(PurchaseOrderProduct::class,'purchase_order_id','id');
    }

    public function quantity(){
        return $this->hasMany(PurchaseOrderProduct::class)->sum('quantity');
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function payments() {
        return $this->hasMany(PurchasePayment::class);
    }
    public function purchase($date){
        $orders = PurchaseOrder::where('date',$date)->get();
        return $orders;
    }
}
