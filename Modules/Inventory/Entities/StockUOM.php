<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;

class StockUOM extends Model
{
    protected $fillable = ['symbol_name','formal_name'];
    protected $table='cadet_inventory_uom';

    
}
