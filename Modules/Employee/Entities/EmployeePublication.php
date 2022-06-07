<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Setting\Entities\Institute;
use Wildside\Userstamps\Userstamps;

class EmployeePublication extends Model
{
    use Userstamps;
    use SoftDeletes;
    // Table name
    protected $table = 'employee_publications';
    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
    // The attributes that should be guarded for arrays.
    protected $guarded = [];

    public function publicationEditions(){
        return $this->hasMany(EmployeePublicationDetails::class,'pub_id','id');
    }
    public function singleInstitute(){
        return $this->belongsTo(Institute::class,'institute_id','id');
    }
}
