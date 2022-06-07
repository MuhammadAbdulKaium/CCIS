<?php

namespace Modules\HealthCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthCareAttachFile extends Model
{
    use SoftDeletes;

    protected $table = "health_care_attach_files";
    protected $guarded = [];

}
