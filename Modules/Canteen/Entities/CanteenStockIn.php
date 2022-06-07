<?php

namespace Modules\Canteen\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanteenStockIn extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_canteen_stock_ins';
    protected $guarded = [];



    public function category()
    {
        return $this->belongsTo(CanteenMenuCategory::class, 'category_id', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(CanteenMenu::class, 'menu_id', 'id');
    }
}
