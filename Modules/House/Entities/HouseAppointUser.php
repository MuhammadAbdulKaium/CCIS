<?php

namespace Modules\House\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Student\Entities\StudentProfileView;

class HouseAppointUser extends Model
{
    protected $table = 'cadet_house_appoint_user';
    protected $guarded = [];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }  

    
    public function house()
    {
        return $this->belongsTo(House::class, 'house_id', 'id');
    }

    public function stuProfile()
    {
        return $this->belongsTo(StudentProfileView::class, 'user_id', 'user_id');
    }

    
    public function appoint()
    {
        return $this->belongsTo(HouseAppoint::class, 'appoint_id', 'id');
    }
}
