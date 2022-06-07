<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;

class EventMark extends Model
{
    protected $table = 'cadet_event_marks';
    protected $guarded = [];



    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
