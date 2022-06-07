<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\RoleManagement\Entities\User;

class SalaryStructureHistory extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'pay_salary_structure_history';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
    public function headName()
    {
        return $this->belongsTo(SalaryHead::class,'salary_head_id','id');
    }
    public function userName()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
