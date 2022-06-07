<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsentFineSettingTable extends Migration
{
    public function up()
    {
        Schema::create('absent_fine_setting', function (Blueprint $table) {
		$table->increments('id');
		$table->integer('institution_id');
		$table->integer('campus_id');
		$table->integer('class');
		$table->integer('period');
		$table->string('status')->nullable();
		$table->timestamp('created_at')->nullable();
		$table->timestamp('updated_at')->nullable();
		$table->timestamp('deleted_at')->nullable();
		$table->bigInteger('created_by')->nullable();
		$table->bigInteger('updated_by')->nullable();
		$table->bigInteger('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absent_fine_setting');
    }
}