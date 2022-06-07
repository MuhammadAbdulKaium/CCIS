<?php

namespace Modules\Inventory\Entities;

use App\CentralizeBaseModel;

class VendorModel extends CentralizeBaseModel
{
    protected $table= 'inventory_vendor_info';
    protected $guarded = ['id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function boot()
    {
        parent::adminBoot();
    }
}
