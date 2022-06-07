<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetApprovalNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_approval_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('menu_name');
            $table->string('unique_name');
            $table->text('menu_link');
            $table->integer('menu_id');
            $table->integer('approval_level');
            $table->integer('action_status')->comment('0-Pending, 1-Approved, 2-Partially Approved, 3-Rejected')->default(0);
            $table->longText('approval_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('cadet_approval_notifications');
    }
}
