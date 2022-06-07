<?php

namespace Modules\Canteen\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Entities\StockUOM;

class CanteenMenu extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_canteen_menus';
    protected $guarded = [];




    public function category()
    {
        return $this->belongsTo(CanteenMenuCategory::class, 'category_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(StockUOM::class, 'uom_id', 'id');
    }
}
