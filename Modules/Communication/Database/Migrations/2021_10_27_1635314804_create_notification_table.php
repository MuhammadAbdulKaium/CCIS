<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('notification_msg_id')->unsigned()->nullable();
        $table->foreign('notification_msg_id')->references('id')->on('notification_message')->onUpdate('cascade')->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification');
    }
}