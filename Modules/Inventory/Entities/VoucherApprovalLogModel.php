<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;
use App\User;

class VoucherApprovalLogModel extends Model
{
    use InventoryHelper;
    protected $table= 'inventory_voucher_approval_log';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeModule($query)
    {
        return $query->where('inventory_voucher_approval_log.campus_id', self::getCampusId())->where('inventory_voucher_approval_log.institute_id', self::getInstituteId());

    }
    public function scopeValid($query)
    {
        return $query->where('inventory_voucher_approval_log.valid', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approval_id', 'id');
    }
}
