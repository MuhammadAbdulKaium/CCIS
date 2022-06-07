<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccountsConfigModel extends Model
{
    protected $table = 'accounts_chart_of_acc_code_config';
    protected $fillable = ['code_type'];
}
