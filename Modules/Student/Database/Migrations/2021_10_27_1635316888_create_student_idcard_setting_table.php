<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentIdcardSettingTable extends Migration
{
    public function up()
    {
        Schema::create('student_idcard_setting', function (Blueprint $table) {

		$table->increments('id');
		$table->integer('institution_id');
		$table->integer('campus_id');
		$table->string('signature')->nullable();
		$table->string('idcard_valid')->nullable();
		$table->string('status')->nullable();
		$table->timestamp('created_at')->nullable();
		$table->timestamp('updated_at')->nullable();
		$table->integer('created_by')->nullable();
		$table->integer('updated_by')->nullable();
		$table->integer('deleted_by')->nullable();
		$table->timestamp('deleted_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('student_idcard_setting');
    }
}