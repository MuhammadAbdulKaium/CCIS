<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveAssign extends Model
{
    protected $table='employee_leave_assign';
    protected $fillable = ['emp_id','leave_structure_id','leave_duration'];

    public function leaveStructureDetail()
    {
        return $this->belongsTo(EmployeeLeaveStructure::class,'leave_structure_id','id');
    }
}
