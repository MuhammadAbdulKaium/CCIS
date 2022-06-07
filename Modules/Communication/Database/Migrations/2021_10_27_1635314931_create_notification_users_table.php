<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationUsersTable extends Migration
{
    public function up()
    {
        Schema::create('notification_users', function (Blueprint $table) {

		$table->increments('id');
		$table->integer('notification_id')->unsigned();
         $table->foreign('notification_id')->references('id')->on('notification')->onUpdate('cascade')->onDelete('cascade');
		$table->integer('user_id')->unsigned();
       $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('notification_path')->nullable();
		$table->integer('is_read')->unsigned();
		$table->integer('status')->unsigned();
		$table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamp('created_at')->nullable();
		$table->timestamp('updated_at')->nullable();
		$table->timestamp('deleted_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_users');
    }
}