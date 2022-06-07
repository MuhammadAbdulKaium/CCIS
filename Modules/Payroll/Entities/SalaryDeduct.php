<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class SalaryDeduct extends Model
{
    protected $fillable = ['amount'];
    protected $table = 'salary_deducts';
}
