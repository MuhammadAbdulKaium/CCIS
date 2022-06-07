<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class salaryStructure extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'pay_salary_structure';

    // The attribute that should be used for softdelete.
//    protected $dates = ['deleted_at'];

    protected $fillable =['amount','min_amount','max_amount'];

    public function headName()
    {
        return $this->belongsTo(SalaryHead::class,'salary_head_id','id');
    }
}
