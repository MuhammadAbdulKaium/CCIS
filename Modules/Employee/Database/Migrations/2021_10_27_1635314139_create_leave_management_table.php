<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveManagementTable extends Migration
{
    public function up()
    {
        Schema::create('leave_management', function (Blueprint $table) {
            $table->id();
		$table->integer('emp_id');
		$table->integer('dept_id');
		$table->integer('designation_id');
		$table->integer('total_leave');
		$table->integer('avilable_leave');
		$table->string('leave_year');
		$table->integer('leave_type');
		$table->integer('campus_id');
		$table->integer('institute_id');
		$table->integer('created_at');
		$table->integer('created_by');
		$table->integer('deleted_at');
		$table->integer('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_management');
    }
}