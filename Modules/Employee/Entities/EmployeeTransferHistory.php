<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Entities\Institute;
use Mpdf\Output\Destination;

class EmployeeTransferHistory extends Model
{
    protected $table = 'employee_transfer_histories';
    protected $guarded = [];

    
    
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo(EmployeeDesignation::class, 'designation_id', 'id');
    }
}
