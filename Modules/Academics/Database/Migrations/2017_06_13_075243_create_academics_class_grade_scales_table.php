<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicsClassGradeScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_class_grade_scales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scale_id')->unsigned()->default(0);
            $table->integer('section_id')->unsigned()->default(0);
            $table->integer('batch_id')->unsigned()->default(0);
            $table->integer('level_id')->unsigned()->nullable();
            $table->foreign('level_id')->references('id')->on('academics_level')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('academic_year_id')->unsigned()->default(0);
            $table->integer('campus')->unsigned()->nullable();
            $table->integer('institute')->unsigned()->nullable();
            $table->unsignedInteger('cs_shift')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academics_class_grade_scales');
    }
}
