<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class BankBranchDetails extends Model
{
    protected $fillable = ['branch_name','bank_id','branch_phone','branch_location'];

    public function bankName()
    {
        return $this->belongsTo(BankDetails::class,'bank_id','id');
    }
}
