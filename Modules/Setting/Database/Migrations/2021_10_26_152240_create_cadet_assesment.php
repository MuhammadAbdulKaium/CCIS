<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetAssesment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_assesment', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('student_id');
            $table->integer('campus_id');
            $table->integer('institute_id');
            $table->integer('academics_year_id');
            $table->integer('academics_level_id')->default(0);
            $table->integer('section_id');
            $table->integer('batch_id');
            $table->dateTime('date');
            $table->integer('cadet_performance_category_id')->nullable();
            $table->integer('cadet_performance_activity_id')->nullable();
            $table->integer('cadet_performance_activity_point_id')->nullable();
            $table->integer('performance_category_id')->nullable();
            $table->float('total_point',10,2)->nullable();
            $table->integer('type')->comment('1=performance, 2=psychology, 3=hobby, 4=aim, 5=dream, 6=idol');
            $table->text('remarks');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('');
    }
}
