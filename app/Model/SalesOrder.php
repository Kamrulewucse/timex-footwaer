<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
//    use SoftDeletes;
    protected $guarded = [];

    protected $dates = ['date', 'next_payment','created_at'];

    public function products() {
        return $this->hasMany(SalesOrderProduct::class);
    }

    public function notApproveProducts() {
        return $this->hasMany(SalesOrderProduct::class)->where('warehouse_id',2);
    }

    public function quantity(){
        return $this->hasMany(SalesOrderProduct::class)->sum('quantity');
    }

    public function customer() {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function companyBranch() {
        return $this->belongsTo(CompanyBranch::class);
    }

    public function user() {
        return $this->hasOne('App\User','user_id');
    }
    public function getsaleOrderProductAttribute(){
        return $this->hasMany(SalesOrderProduct::class,'sale_order_id')->sum('quantity');
    }
    public function sale($date){
        $orders = SalesOrder::where('date',$date)->get();
        return $orders;
    }
}
