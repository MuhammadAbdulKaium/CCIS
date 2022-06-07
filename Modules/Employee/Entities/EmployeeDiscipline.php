<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Setting\Entities\Institute;
use Wildside\Userstamps\Userstamps;

class EmployeeDiscipline extends Model
{
    use Userstamps;
    use SoftDeletes;
    // Table name
    protected $table = 'employee_disciplines';
    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
    // The attributes that should be guarded for arrays.
    protected $guarded = [];

    public function singlePunishmentBy(){
        return $this->belongsTo(EmployeeInformation::class,'punishment_by_select','id');
    }

    public function singleInstitute(){
        return $this->belongsTo(Institute::class,'institute_id','id');
    }
}
