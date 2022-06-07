<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Setting\Entities\Institute;

class EmployeeDocument extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_employee_documents';

    protected $guarded = [];

    public function institute(){
        return $this->belongsTo(Institute::class,'institute_id','id');
    }
}
