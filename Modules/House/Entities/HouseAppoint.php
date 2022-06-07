<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseAppoint extends Model
{
    use SoftDeletes;
    protected $table = 'cadet_house_appoints';
    protected $guarded = [];

    
    public function appointHistories()
    {
        return $this->hasMany(HouseAppointHistory::class, 'appoint_id', 'id');
    }
}
