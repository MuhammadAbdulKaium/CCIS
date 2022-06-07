<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeAcrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_acrs', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->year('year');
            $table->integer('initiative_officer');
            $table->integer('higher_officer');
            $table->integer('io')->comment("0=>pending, 1=>approved,2=>Not Approved");
            $table->integer('ho')->comment("0=>pending, 1=>approved, 2=>Not Approved");
            $table->integer('io_name');
            $table->integer('ho_name');
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
        Schema::dropIfExists('employee_acrs');
    }
}
