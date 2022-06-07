<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetPerformanceActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_performance_activity', function (Blueprint $table) {
            $table->id();
            $table->integer('cadet_category_id')->unsigned();
            // $table->foreign('cadet_category_id')->references('id')->on('cadet_performance_category')->onDelete('cascade');
            $table->string('activity_name');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_performance_activity');
    }
}
