<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituteSmsPriceTable extends Migration
{
    public function up()
    {
        Schema::create('institute_sms_price', function (Blueprint $table) {

		$table->increments('id');
		$table->string('institution_id');
		$table->float('sms_price');
		$table->timestamp('created_at')->nullable();
		$table->timestamp('updated_at')->nullable();
		$table->integer('created_by')->nullable();
		$table->integer('updated_by')->nullable();
		$table->timestamp('deleted_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('institute_sms_price');
    }
}