<?php

namespace Modules\UserRoleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuRoute extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_menu_routes';

    protected $guarded = [];
}
