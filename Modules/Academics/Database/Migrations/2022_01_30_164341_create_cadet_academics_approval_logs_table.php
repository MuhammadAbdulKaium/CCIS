<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetAcademicsApprovalLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_academics_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('menu_id');
            $table->string('menu_type');
            $table->integer('user_id');
            $table->integer('approval_layer');
            $table->integer('action_status');
            $table->string('comments')->nullable();
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('cadet_academics_approval_logs');
    }
}
