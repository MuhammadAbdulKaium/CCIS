<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeePublicationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_publication_details', function (Blueprint $table) {
            $table->id();
            $table->integer('pub_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->date('date');
            $table->string('editions');
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
        Schema::dropIfExists('employee_publication_details');
    }
}
