<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\RoleManagement\Entities\User;
use Modules\Setting\Entities\Institute;

class EmployeePromotion extends Model
{
    protected $fillable = [
        'employee_id',
        'previous_department',
        'previous_designation',
        'previous_category',
        'department',
        'designation',
        'promotion_board','reasoning','board_remarks',
        'category',
        'status',
        'campus',
        'institute',
        'prev_campus',
        'prev_institute',
        'last_promotion_date',
        'promotion_date',
        'created_by',
        'updated_by',
        'approved_by'
    ];
    /**
     * @var mixed
     */


    use SoftDeletes;
    public function employee(){
        return $this->belongsTo(EmployeeInformation::class,'employee_id','id');
    }
    public function authorized(){
        return $this->hasOne(User::class,'id','approved_by');
    }
    public function singleDesignation(){
        return $this->belongsTo(EmployeeDesignation::class,'designation','id');
    }
    
}
