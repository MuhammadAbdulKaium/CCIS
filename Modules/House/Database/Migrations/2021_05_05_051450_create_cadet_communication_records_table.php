<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetCommunicationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_communication_records', function (Blueprint $table) {
            $table->id();
            $table->integer('house_id');
            $table->integer('student_id');
            $table->integer('admission_year_id');
            $table->integer('academic_year_id');
            $table->date('date');
            $table->integer('month');
            $table->integer('mode');
            $table->time('from_time');
            $table->time('to_time');
            $table->text('communication_topics');
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
        Schema::dropIfExists('cadet_communication_records');
    }
}
