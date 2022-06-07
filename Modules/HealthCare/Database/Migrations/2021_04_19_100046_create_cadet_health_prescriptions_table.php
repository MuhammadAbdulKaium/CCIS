<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetHealthPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_health_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('house')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('patient_type');
            $table->integer('patient_id');
            $table->string('follow_up')->nullable();
            $table->longText('content');
            $table->integer('status');
            $table->longText('history')->nullable();
            $table->integer('days_in_hospital')->default(0);
            $table->integer('score')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('cadet_health_prescriptions');
    }
}
