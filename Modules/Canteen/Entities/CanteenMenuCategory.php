<?php

namespace Modules\Canteen\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanteenMenuCategory extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_canteen_menu_categories';
    protected $guarded = [];




    public function menus()
    {
        return $this->hasMany(CanteenMenu::class, 'category_id', 'id');
    }
}
