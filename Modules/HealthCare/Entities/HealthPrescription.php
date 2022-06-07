<?php

namespace Modules\HealthCare\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\House\Entities\House;
use Modules\Student\Entities\StudentInformation;

class HealthPrescription extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_health_prescriptions';
    protected $guarded = [];



    public function investigations()
    {
        return $this->hasMany(HealthInvestigationReport::class, 'prescription_id', 'id');
    }

    public function drugReports()
    {
        return $this->hasMany(HealthDrug::class, 'prescription_id', 'id');
    }

    public function cadet()
    {
        return $this->belongsTo(StudentInformation::class, 'patient_id', 'id')->with('singleUser');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeInformation::class, 'patient_id', 'id')->with('singleUser');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function attachFile(){
        return $this->hasMany(HealthCareAttachFile::class, 'pr_id','id');
    }
    public function singleHouse(){
        return $this->belongsTo(House::class,'house','id');
    }
}
