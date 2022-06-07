<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadetWarning extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_warnings';
    protected $guarded = [];
}
