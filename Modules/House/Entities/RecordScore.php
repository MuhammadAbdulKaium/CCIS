<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Academics\Entities\AcademicsAdmissionYear;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Semester;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Student\Entities\StudentProfileView;

class RecordScore extends Model
{
    protected $table = 'cadet_house_record_scores';
    protected $guarded = [];




    public function student()
    {
        return $this->belongsTo(StudentProfileView::class, 'student_id', 'std_id');
    }

    public function admissionYear()
    {
        return $this->belongsTo(AcademicsAdmissionYear::class, 'admission_year_id', 'id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicsYear::class, 'academic_year_id', 'id');
    }

    public function term()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(CadetPerformanceType::class, 'category_id', 'id');
    }
}
