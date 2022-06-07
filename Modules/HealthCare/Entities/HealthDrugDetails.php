<?php

namespace Modules\HealthCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthDrugDetails extends Model
{
    use SoftDeletes;
    protected $table = 'cadet_health_drug_details';
    protected $guarded = [];
}
