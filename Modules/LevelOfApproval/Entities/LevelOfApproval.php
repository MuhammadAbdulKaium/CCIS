<?php

namespace Modules\LevelOfApproval\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LevelOfApproval extends Model
{
    use SoftDeletes;
    protected $table = 'cadet_level_of_approvals';
    protected $guarded = [];

    
    
    public function approvalLayers()
    {
        return $this->hasMany(ApprovalLayer::class, 'level_of_approval_unique_name', 'unique_name');
    }
}
