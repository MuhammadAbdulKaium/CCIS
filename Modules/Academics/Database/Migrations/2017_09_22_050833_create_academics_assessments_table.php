<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicsAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('grading_category_id')->unsigned()->nullable();
            $table->foreign('grading_category_id')->references('id')->on('academics_grade_categories')->onDelete('cascade');
            $table->integer('grade_id')->unsigned()->nullable();
            $table->foreign('grade_id')->references('id')->on('academics_grades')->onDelete('cascade');
            $table->integer('points')->unsigned()->nullable();
            $table->integer('passing_points')->unsigned()->nullable();
            $table->string('status')->nullable();
            $table->string('counts_overall_score')->nullable();
            $table->string('applied_to')->nullable();
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
        Schema::dropIfExists('academics_assessments');
    }
}
