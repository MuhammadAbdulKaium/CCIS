<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLeaveStructure extends Model
{
    use SoftDeletes;

    // Table name
    protected $fillable=['id','leave_name_alias','leave_type','doj','leave_name','leave_duration','cf','year_closing','year_closing_month','holidayEffect','encash','salaryType','salary_type_percentage'];
    protected $table = 'employee_leave_structures';



}
