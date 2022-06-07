<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Setting\Entities\Institute;

class HouseAppointHistory extends Model
{
    use SoftDeletes;
    protected $table = 'cadet_house_appoint_histories';
    protected $guarded = [];

    
    
    public function appoint()
    {
        return $this->belongsTo(HouseAppoint::class, 'appoint_id', 'id');
    }

    
    public function house()
    {
        return $this->belongsTo(House::class, 'house_id', 'id');
    }

    public function institute(){
        return $this->belongsTo(Institute::class,'institute_id','id');
    }
}
