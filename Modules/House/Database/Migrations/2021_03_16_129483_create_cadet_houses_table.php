<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_houses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('bengali_name')->nullable();
            $table->string('alias');
            $table->integer('no_of_floors');
            $table->integer('employee_id');
            $table->longText('house_master_history');
            $table->string('motto')->nullable();
            $table->string('bengali_motto')->nullable();
            $table->string('color_name')->nullable();
            $table->string('color')->nullable();
            $table->string('symbol_name')->nullable();
            $table->string('symbol')->nullable();
            $table->integer('student_id')->nullable();
            $table->longText('house_prefect_history')->nullable();
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
        Schema::dropIfExists('cadet_houses');
    }
}
