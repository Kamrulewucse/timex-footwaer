<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SalesOrderProduct extends Model
{
    protected $guarded = [];

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function productColor()
    {
        return $this->belongsTo(ProductColor::class);
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function purchaseInventory()
    {
        return $this->belongsTo(PurchaseInventory::class);
    }

    public function model()
    {
        return $this->belongsTo(ProductItem::class,'product_item_id','id');
    }
    public function size()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }
}
