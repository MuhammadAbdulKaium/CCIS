<?php

namespace Modules\Canteen\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanteenMenuRecipe extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_canteen_menu_recipes';
    protected $guarded = [];




    public function menu()
    {
        return $this->belongsTo(CanteenMenu::class, 'menu_id', 'id');
    }
}
