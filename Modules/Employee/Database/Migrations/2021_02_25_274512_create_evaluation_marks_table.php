<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('evaluation_id');
            $table->integer('score_by');
            $table->integer('score_for');
            $table->longText('parameters_score');
            $table->integer('total');
            $table->integer('on100');
            $table->year('year');
            $table->text('remarks')->nullable();
            $table->integer('score_by_type');
            $table->integer('score_for_type');
            $table->integer('score_by_designation')->nullable();
            $table->integer('score_for_designation')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('campus_id');
            $table->integer('institute_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation_marks');
    }
}
