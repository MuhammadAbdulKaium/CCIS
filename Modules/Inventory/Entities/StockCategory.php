<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class StockCategory extends Model
{
    protected $fillable = ['stock_category_name','stock_category_parent_id','has_child'];
    protected $table='cadet_inventory_stock_category';

    use InventoryHelper;

    
}
