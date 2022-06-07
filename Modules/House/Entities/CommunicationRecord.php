<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\AcademicsAdmissionYear;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Student\Entities\StudentProfileView;

class CommunicationRecord extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_communication_records';
    protected $guarded = [];



    public function student()
    {
        return $this->belongsTo(StudentProfileView::class, 'student_id', 'std_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicsYear::class, 'academic_year_id', 'id');
    }

    public function admissionYear()
    {
        return $this->belongsTo(AcademicsAdmissionYear::class, 'admission_year_id', 'id');
    }
}
