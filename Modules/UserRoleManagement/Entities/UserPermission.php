<?php

namespace Modules\UserRoleManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'cadet_user_permissions';
    protected $guarded = [];
}
