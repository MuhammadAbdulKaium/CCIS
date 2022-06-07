<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetBoardExamResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_board_exam_results', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id');
            $table->integer('section_id');
            $table->integer('student_id');
            $table->integer('academic_year_id');
            $table->string('session_year')->nullable();
            $table->enum('board_exam_type',['PSC', 'JSC','SSC','HSC'])->nullable();
            $table->enum('board_name',['dhaka', 'jessore','rajshahi','chittagong','barishal','comilla','sylhet','dinajpur'])->nullable();
            $table->bigInteger('board_exam_roll')->nullable();
            $table->string('board_exam_reg')->nullable();
            $table->string('total_gpa')->nullable();
            $table->integer('total_marks')->nullable();
            $table->float('total_score',8,2)->nullable();
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
        Schema::dropIfExists('cadet_board_exam_results');
    }
}
