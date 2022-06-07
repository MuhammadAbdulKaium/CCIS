<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SalaryGrade extends Model
{
    use SoftDeletes;

    protected $table = 'pay_salary_grade';
    protected $dates = ['deleted_at'];

    protected $fillable = [];

}
