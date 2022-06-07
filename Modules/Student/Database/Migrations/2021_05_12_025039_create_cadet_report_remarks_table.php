<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetReportRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_report_remarks', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('employee_id');
            $table->string('designation');
            $table->integer('academic_year_id')->nullable();
            $table->integer('semester_id')->nullable();
            $table->integer('score');
            $table->text('remarks');
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
        Schema::dropIfExists('cadet_report_remarks');
    }
}
