<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveApprovalLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('menu_id');
            $table->string('menu_type');
            $table->integer('user_id');
            $table->integer('approval_layer');
            $table->integer('action_status');
            $table->string('comments')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('campus_id');
            $table->integer('institute_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_approval_logs');
    }
}
