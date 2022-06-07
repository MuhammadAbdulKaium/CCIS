<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentRegistersTable extends Migration
{
    public function up()
    {
        Schema::create('student_registers', function (Blueprint $table) {

		$table->increments('id');
		$table->integer('std_id')->nullable();
		$table->string('ssc')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('student_registers');
    }
}