<?php

namespace Modules\Mess\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\House\Entities\House;

class MessTable extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_mess_tables';
    protected $guarded = [];


    public function employee()
    {
        return $this->belongsTo(EmployeeInformation::class, 'employee_id', 'id');
    }

    public function house()
    {
        return $this->belongsTo(House::class, 'house_id', 'id');
    }
}
