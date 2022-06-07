<?php

namespace Modules\Employee\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDesignation extends Model
{
    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'employee_designations';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'alias',
        'dept_id',
        'campus_id',
        'institute_id'
    ];

    // return the department profile
    public function department(){
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDepartment', 'dept_id', 'id')->first();
    }

    public function singleDepartment(){
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDepartment', 'dept_id', 'id');
    }


    
    
    public function evaluationBy()
    {
        return $this->belongsToMany('Modules\Employee\Entities\Evaluation', 'employee_designation_evaluation_by', 'employee_designation_id', 'evaluation_by_id');
    }

    public function evaluationById()
    {
        return $this->belongsToMany('Modules\Employee\Entities\Evaluation', 'employee_designation_evaluation_by', 'employee_designation_id', 'evaluation_by_id')->get()->pluck('id');
    }

    // return createdBy
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    // return updatedBy
    public function updatedBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
