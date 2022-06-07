<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class SalaryScale extends Model
{
    protected $fillable = ['scale_name','grade_id','minimum_amt','maximum_amt'];
    protected $table='salary_scales';

    public function gradeName()
    {
        return $this->belongsTo(SalaryGrade::class,'grade_id','id');
    }
}
