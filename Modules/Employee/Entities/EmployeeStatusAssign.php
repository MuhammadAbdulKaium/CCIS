<?php

namespace Modules\Employee\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Plugin\ForcedCopy;

class EmployeeStatusAssign extends Model
{
    protected $guarded = [];
    public function employee(){
        return $this->belongsTo(EmployeeInformation::class,'id','employee_id');
    }
    public function status(){
        return $this->belongsTo('Modules\Employee\Entities\EmployeeStatus','status_id','id');
    }
    public function assignedBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
