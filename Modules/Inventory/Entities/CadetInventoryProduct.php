<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;

class CadetInventoryProduct extends Model
{
    //    protected $fillable = ['product_name'];
    protected $table = 'cadet_stock_products';
    protected $casts = ['store_tagging' => 'array'];



    public function stockGroup()
    {
        return $this->belongsTo(StockGroup::class, 'stock_group', 'id');
    }

    public function stockCategory()
    {
        return $this->belongsTo(StockCategory::class, 'category_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo('Modules\Inventory\Entities\StockUOM', 'unit', 'id');
    }

    // public function productStockInDetail(){
    //     return $this->hasMany('Modules\Inventory\Entities\StockInDetailsModel', 'item_id', 'id');
    // }

    // public function productStockOutDetail(){
    //     return $this->hasMany('Modules\Inventory\Entities\StockOutDetailsModel', 'item_id', 'id');
    // }
}
