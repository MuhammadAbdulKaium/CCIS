<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardExamMarkDetail extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_board_exam_mark_details';

    protected $fillable = [
        'cadet_board_exam_result_id',
        'subject_id',
        'subject_gpa',
        'subject_marks',
        'subject_score'
    ];
    public function boardExamResult(){
       return $this->belongsTo('Modules\Academics\Entities\BoardExamResult','id','cadet_board_exam_result_id');
    }
}
