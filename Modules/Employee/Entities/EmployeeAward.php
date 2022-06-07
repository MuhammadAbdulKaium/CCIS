<?php

namespace Modules\Employee\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Setting\Entities\Institute;

class EmployeeAward extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function institute(){
        return $this->belongsTo(Institute::class,'institute_id','id');
    }

    public function awardedBy(){
        return $this->belongsTo(EmployeeInformation::class,'awarded_by_employee','id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
