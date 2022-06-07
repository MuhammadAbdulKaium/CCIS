<?php

namespace Modules\CadetFees\Entities;

use Illuminate\Database\Eloquent\Model;

class CadetFeesHistory extends Model
{
    protected $fillable = ['std_id','gr_no','academic_level','batch','section','academic_year','tution_fees','created_by'];
    protected $table='student_tution_fees_history';
}
