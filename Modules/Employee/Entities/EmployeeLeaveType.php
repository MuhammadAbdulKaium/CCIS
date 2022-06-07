<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLeaveType extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'employee_leave_types';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

}
