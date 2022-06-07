<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetApprovalLayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_approval_layers', function (Blueprint $table) {
            $table->id();
            $table->string('level_of_approval_unique_name');
            $table->integer('layer');
            $table->integer('role_id')->nullable();
            $table->longText('user_ids')->nullable();
            $table->enum('all_members', array('yes'))->nullable();
            $table->float('po_value')->nullable();
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
        Schema::dropIfExists('cadet_approval_layers');
    }
}
