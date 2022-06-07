<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academics_year_id')->unsigned()->nullable();
            $table->foreign('academics_year_id')->references('id')->on('academics_year')->onDelete('cascade');
            $table->integer('batch_id')->unsigned()->nullable();
            $table->foreign('batch_id')->references('id')->on('batch')->onDelete('cascade');
            $table->integer('division_id')->nullable();
            $table->string('section_name');
            $table->string('intake')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section');
    }
}
