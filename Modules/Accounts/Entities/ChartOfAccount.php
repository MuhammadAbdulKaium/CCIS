<?php

namespace Modules\Accounts\Entities;

use App\CentralizeBaseModel;
use App\Helpers\AccountsHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartOfAccount extends CentralizeBaseModel
{
    use AccountsHelper;

    protected $table = 'accounts_chart_of_accounts';
    protected $guarded = [];

    public static function boot()
    {
        parent::adminBoot();
    }
}
