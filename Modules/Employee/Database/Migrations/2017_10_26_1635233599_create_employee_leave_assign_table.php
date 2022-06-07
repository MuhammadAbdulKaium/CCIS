<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveAssignTable extends Migration
{
    public function up()
    {
        Schema::create('employee_leave_assign', function (Blueprint $table) {
		$table->increments('id');
		$table->integer('emp_id');
		$table->integer('dept_id');
		$table->integer('designation_id');
		$table->integer('leave_type_id');
		$table->tinyInteger('leave_status')->default('1');
		$table->integer('duration');
		$table->integer('leave_process_procedure');
		$table->integer('inst_id');
		$table->integer('campus_id');
		$table->timestamps();
		$table->integer('created_by')->nullable();
		$table->integer('deleted_by')->nullable();
		$table->integer('updated_by')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_leave_assign');
    }
}