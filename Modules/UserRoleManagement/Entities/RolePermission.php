<?php

namespace Modules\UserRoleManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'cadet_role_permissions';

    protected $guarded = [];
}
