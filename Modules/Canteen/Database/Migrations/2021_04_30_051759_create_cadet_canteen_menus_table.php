<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetCanteenMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_canteen_menus', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('menu_name');
            $table->integer('uom_id');
            $table->integer('cost');
            $table->integer('sell_price');
            $table->longText('effective_cost_dates');
            $table->integer('available_qty')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('campus_id');
            $table->integer('institute_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_canteen_menus');
    }
}
