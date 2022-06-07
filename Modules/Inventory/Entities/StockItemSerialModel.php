<?php

namespace Modules\Inventory\Entities;
use Illuminate\Database\Eloquent\Model;

class StockItemSerialModel extends Model
{
	protected $table= 'inventory_item_serial_info';
    protected $fillable = ['item_id', 'serial_from', 'serial_to'];

    public function scopeValid($query)
    {
        return $query->where('inventory_item_serial_info.valid', 1);
    }

}
