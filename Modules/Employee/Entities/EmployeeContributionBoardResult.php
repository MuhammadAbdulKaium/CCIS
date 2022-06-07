<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Modules\Academics\Entities\Subject;
use Modules\Setting\Entities\Institute;

class EmployeeContributionBoardResult extends Model
{
    use Userstamps;
    use SoftDeletes;
    // Table name
    protected $table = 'employee_contribution_board_results';
    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
    // The attributes that should be guarded for arrays.
    protected $guarded = [];

    public function getSubject($id){
        $subject = Subject::findOrFail($id);
        return $subject->subject_name;
    }
    public function singleInstitute(){
        return $this->belongsTo(Institute::class,'institute_id','id');
    }
   
}
