<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailReportLayout extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_detail_report_layouts';
    protected $guarded = [];
}
