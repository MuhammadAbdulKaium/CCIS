<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;

class InventoryStore extends Model
{
    protected $fillable = ['store_name','store_address_1','store_address_2','store_phone','store_city'];
    protected $table='cadet_inventory_store';


    public function storeCategory()
    {
        return $this->hasMany(InventoryStoreCategory::class,'id','category_id');
    }

    public function scopeAccess($query, $that)
    {
        return $query->where(function($q)use($that){
            if(count($that->AccessStore)>0){
                $q->whereIn('cadet_inventory_store.id', $that->AccessStore);
            }
        });

    }
}
