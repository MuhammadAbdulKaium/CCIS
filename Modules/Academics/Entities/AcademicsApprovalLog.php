<?php

namespace Modules\Academics\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AcademicsApprovalLog extends Model
{
    protected $table = 'cadet_academics_approval_logs';
    protected $guarded = [];

    
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
