<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class SalaryGenerateList extends Model
{
    protected $fillable = ['processed','sheet_id'];
    protected $table = 'salary_generate_list';
}
