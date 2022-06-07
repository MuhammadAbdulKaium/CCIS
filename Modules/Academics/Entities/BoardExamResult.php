<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BoardExamResult extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_board_exam_results';

    protected $guarded = [];

    protected $fillable = [
        'batch_id',
        'section_id',
        'student_id',
        'academic_year_id',
        'session_year',
        'board_exam_type',
        'board_name',
        'board_exam_roll',
        'board_exam_reg',
        'total_gpa',
        'total_marks',
        'total_score',
        'total_score',
    ];
    public function boardExamSubject()
    {
      return $this->hasMany('Modules\Academics\Entities\BoardExamMarkDetail','cadet_board_exam_result_id','id');
    }
}
