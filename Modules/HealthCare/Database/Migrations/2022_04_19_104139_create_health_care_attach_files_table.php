<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHealthCareAttachFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_care_attach_files', function (Blueprint $table) {
            $table->id();
            $table->integer('pr_id')->nullable();
            $table->string('file')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('campus_id')->nullable();
            $table->integer('institute_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_care_attach_files');
    }
}
