<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetMenuRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_menu_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->integer('parent_uid')->nullable();
            $table->integer('has_child');
            $table->enum('link_type', ['internal', 'external']);
            $table->string('label');
            $table->string('route_link')->nullable();
            $table->string('route_method')->nullable();
            $table->string('route_name')->nullable();
            $table->integer('order_no');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_menu_routes');
    }
}
