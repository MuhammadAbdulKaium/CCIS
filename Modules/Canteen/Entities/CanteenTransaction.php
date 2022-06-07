<?php

namespace Modules\Canteen\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanteenTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_canteen_transactions';
    protected $guarded = [];
}
