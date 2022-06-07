<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payroll\Entities\BankBranchDetails;

class PocketMoney extends Model
{
    use SoftDeletes;
    protected $table = 'cadet_pocket_money';
    protected $guarded = [];

    
    
    public function histories()
    {
        return $this->hasMany(PocketMoneyHistory::class, 'pocket_money_id', 'id');
    }
    
    public function bankBranch()
    {
        return $this->belongsTo(BankBranchDetails::class, 'bank_branch_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(StudentProfileView::class, 'std_id', 'std_id');
    }
}
