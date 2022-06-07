<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class StockGroup extends Model
{
    protected $table='cadet_inventory_stock_group';
    protected $fillable = ['stock_group_name','parent_group_id','has_child'];
    use InventoryHelper;

    

}
