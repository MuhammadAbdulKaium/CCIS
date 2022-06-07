<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\RoleManagement\Entities\User;

class EmployeeLeaveApplication extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'employee_leave_applications';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'status',
        'leave_structure_id',
        'leave_reason',
        'remarks',
        'start_date',
        'end_date',
        'leave_days',
        'applied_date',
        'employee',
        'designation',
        'department',
        'campus_id',
        'institute_id'
    ];


    public function leaveType()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeLeaveType','leave_type', 'id')->first();
    }

    // returns employee profile
    public function employee()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'employee', 'id' )->first();
    }

    // returns structure profile
    public function designation()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDesignation', 'designation', 'id' )->first();
    }

    // returns department profile
    public function department()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDepartment', 'department', 'id' )->first();
    }

    public function leaveStructureName()
    {
        return $this->belongsTo(EmployeeLeaveStructure::class,'leave_structure_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'employee_id','id');
    }


}
