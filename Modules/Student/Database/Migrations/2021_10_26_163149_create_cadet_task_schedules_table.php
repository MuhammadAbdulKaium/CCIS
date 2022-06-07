<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetTaskSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_task_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('task_schedule_date_id');
            $table->integer('student_activity_category_id');
            $table->integer('student_activity_id');
            $table->integer('event_type')->comment("1 = Daily | 2 = Weekly | 3 = Monthly");
            $table->integer('different_thursday')->nullable();
            $table->longText('times');
            $table->integer('extra_note')->nullable();
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
        Schema::dropIfExists('cadet_task_schedules');
    }
}
