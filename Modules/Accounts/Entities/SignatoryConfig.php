<?php

namespace Modules\Accounts\Entities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SignatoryConfig extends Model
{
    use SoftDeletes;
    protected $fillable = ['reportName','label','empolyee_id','attatch'];

    // designation  level using SignatoryConfig designation
    public function employeeInfo()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation','empolyee_id','id');
        
    }
}
