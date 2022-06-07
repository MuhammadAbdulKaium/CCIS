<?php

namespace Modules\Inventory\Entities;

use Modules\Inventory\Entities\BaseModel;
use App\Helpers\InventoryHelper;

class PurchaseReceiveDetailsModel extends BaseModel
{
    use InventoryHelper;
    protected $table= 'inventory_purchase_receive_details';
    protected $guarded = ['id','campus_id','institute_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('inventory_purchase_receive_details.campus_id', self::getCampusId())->where('inventory_purchase_receive_details.institute_id', self::getInstituteId());

    }
    public function scopeItemAccess($query, $item_ids=[])
    {
        return $query->where(function($q)use($item_ids){
            if(count($item_ids)>0){
                $q->whereIn('inventory_purchase_receive_details.item_id', $item_ids);
            }
        });
    }
    public function scopeValid($query)
    {
        return $query->where('inventory_purchase_receive_details.valid', 1);
    }

    public function detailPurchaseReceiveInfoWise(){
        return $this->belongsTo('Modules\Inventory\Entities\PurchaseReceiveInfoModel', 'pur_receive_id', 'id');
    }
    public function detailPurchaseReceiveProductWise(){
        return $this->belongsTo('Modules\Inventory\Entities\CadetInventoryProduct', 'item_id', 'id');
    }
}
