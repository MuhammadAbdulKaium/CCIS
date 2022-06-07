<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {

		$table->string('id');
		$table->integer('user_id')->nullable();
		$table->string('ip_address',45)->nullable()->default('NULL');
		$table->text('user_agent');
		$table->text('payload');
		$table->integer('last_activity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}