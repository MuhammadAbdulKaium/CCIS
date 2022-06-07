<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Setting\Entities\Institute;
use Wildside\Userstamps\Userstamps;

class EmployeeAcr extends Model
{
    use Userstamps;
    use SoftDeletes;
    // Table name
    protected $table = 'employee_acrs';
    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
    // The attributes that should be guarded for arrays.
    protected $guarded = [];
    public function employeeIoName(){
        return $this->belongsTo(EmployeeInformation::class,'io_name','id');
    }
    public function employeeHoName(){
        return $this->belongsTo(EmployeeInformation::class,'ho_name','id');
    }
    public function singleInstitute(){
        return $this->belongsTo(Institute::class,'institute_id','id');
    }
}
