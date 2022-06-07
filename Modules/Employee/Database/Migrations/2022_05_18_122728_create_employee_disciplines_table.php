<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_disciplines', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->date('occurrence_date');
            $table->string('place');
            $table->text('description');
            $table->string('punishment_category');
            $table->date('punishment_date');
            $table->integer('punishment_by_select')->nullable();
            $table->string('punishment_by_write')->nullable();
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
        Schema::dropIfExists('employee_disciplines');
    }
}
