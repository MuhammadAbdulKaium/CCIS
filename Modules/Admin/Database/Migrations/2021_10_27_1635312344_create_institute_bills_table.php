<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituteBillsTable extends Migration
{
    public function up()
    {
        Schema::create('institute_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('billing_month')->unsigned()->nullable();
            $table->boolean('status')->default(1);
            $table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('institute_bills');
    }
}