<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveManagementHistory extends Model
{
    protected $guarded = [];
    protected $table = 'leave_management_history';
}
