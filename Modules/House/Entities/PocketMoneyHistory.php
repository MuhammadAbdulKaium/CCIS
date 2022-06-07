<?php

namespace Modules\House\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payroll\Entities\BankBranchDetails;
use Modules\Student\Entities\StudentProfileView;

class PocketMoneyHistory extends Model
{
    use SoftDeletes;
    protected $table = 'cadet_pocket_money_histories';
    protected $guarded = [];

    public function bankBranch()
    {
        return $this->belongsTo(BankBranchDetails::class, 'bank_branch_id', 'id');
    }

    
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function student()
    {
        return $this->belongsTo(StudentProfileView::class, 'std_id', 'std_id');
    }
}
