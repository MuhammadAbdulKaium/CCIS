<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScoreSheetDirectory extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_score_sheet_directories';
    protected $guarded = [];
}
