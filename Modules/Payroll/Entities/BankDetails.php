<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    protected $fillable = ['bank_name'];

    
    
    public function branches()
    {
        return $this->hasMany(BankBranchDetails::class, 'bank_id', 'id');
    }
}
