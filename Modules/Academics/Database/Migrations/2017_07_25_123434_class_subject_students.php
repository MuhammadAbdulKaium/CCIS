<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassSubjectStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_subject_students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_limit')->nullable();
            $table->integer('std_admit')->nullable();
            $table->integer('subject_group')->nullable();
            $table->integer('subject_type')->nullable();
            $table->integer('class_id')->nullable();
            $table->integer('year_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_subject_students');
    }
}
