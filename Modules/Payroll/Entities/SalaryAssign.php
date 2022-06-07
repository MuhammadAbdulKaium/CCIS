<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class SalaryAssign extends Model
{
    protected $fillable = ['salary_scale','bank_details_id','bank_branch_details_id','bank_acc_number','salary_amount'];
    protected $table = 'cadet_employee_salary_assign';
}
