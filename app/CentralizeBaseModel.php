<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentralizeBaseModel extends Model
{
    use SoftDeletes;

    public static function adminBoot()
    {
        if(Auth::check()) {
            $person_id = Auth::user()->id;
            self::bootAction($person_id);
        }
    }


    public static function bootAction($person_id)
    {
        parent::boot();

        static::creating(function($model) use ($person_id)
        {
            $model->created_by = $person_id;
        });

        static::updating(function($model) use ($person_id)
        {
            $model->updated_by = $person_id;
        });

        static::deleting(function($model) use ($person_id)
        {
            $model->deleted_by = $person_id;
            $model->update();
        });
    }
}
