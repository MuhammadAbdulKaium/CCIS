<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeContributionBoardResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_contribution_board_results', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->year('exam_years');
            $table->string('exam_name');
            $table->integer('total_cadet');
            $table->integer('gpa_type')->comment('0=>without Subject, 1=>Assign Subject');
            $table->longText('total_gpa');
            $table->longText('not_total_gpa');
            $table->string('attachment')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('campus_id')->nullable();
            $table->integer('institute_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('employee_contribution_board_results');
    }
}
