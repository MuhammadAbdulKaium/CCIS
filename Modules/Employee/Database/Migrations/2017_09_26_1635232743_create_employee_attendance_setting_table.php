<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendanceSettingTable extends Migration
{
    public function up()
    {
        Schema::create('employee_attendance_setting', function (Blueprint $table) {

		$table->increments('id');
		$table->integer('institution_id');
		$table->integer('campus_id');
		$table->integer('emp_id');
		$table->time('start_time');
		$table->time('end_time');
		$table->string('status')->default('0');
        $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_attendance_setting');
    }
}