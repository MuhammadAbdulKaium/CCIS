<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicsYearSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_year_semesters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_year_id')->unsigned()->nullable();
            $table->string('name')->default('not set');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('academics_year_semesters');
    }
}
