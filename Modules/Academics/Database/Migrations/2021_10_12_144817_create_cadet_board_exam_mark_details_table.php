<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetBoardExamMarkDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_board_exam_mark_details', function (Blueprint $table) {
            $table->id();
            $table->integer('cadet_board_exam_result_id');
            $table->integer('subject_id');
            $table->integer('subject_marks')->nullable();
            $table->float('subject_score',8,2)->nullable();
            $table->string('subject_gpa')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_board_exam_mark_details');
    }
}
