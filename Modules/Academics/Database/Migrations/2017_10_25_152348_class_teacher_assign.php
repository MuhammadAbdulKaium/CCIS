<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassTeacherAssign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_teacher_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institute_id');
            $table->integer('campus_id');
            $table->integer('teacher_id');
            $table->integer('batch_id');
            $table->integer('section_id');
            $table->integer('status')->default('1');
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
        //
    }
}
