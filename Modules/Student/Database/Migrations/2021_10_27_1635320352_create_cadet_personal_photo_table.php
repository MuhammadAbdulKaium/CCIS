<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadetPersonalPhotoTable extends Migration
{
    public function up()
    {
        Schema::create('cadet_personal_photo', function (Blueprint $table) {

		$table->increments('id');
		$table->string('image',100);
		$table->string('cadet_no',20);
		$table->date('date');
		$table->integer('student_id')->nullable();
		$table->integer('campus_id');
		$table->integer('institute_id');
		$table->integer('academics_year_id');
		$table->integer('section_id');
		$table->integer('batch_id');
		$table->date('created_at')->nullable();
		$table->date('updated_at')->nullable();
		$table->integer('created_by')->nullable();
		$table->integer('updated_by')->nullable();
		$table->date('deleted_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('cadet_personal_photo');
    }
}