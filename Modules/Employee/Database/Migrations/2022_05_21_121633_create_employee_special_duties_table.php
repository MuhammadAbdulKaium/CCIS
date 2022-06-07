<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSpecialDutiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_special_duties', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('campus_id')->nullable();
            $table->integer('institute_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_special_duties');
    }
}
