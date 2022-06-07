<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetHealthDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_health_drugs', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_type')->comment("1=cadet, 2=employee");
            $table->integer('patient_id');
            $table->integer('prescription_id');
            $table->integer('product_id');
            $table->integer('required_quantity');
            $table->integer('disbursed_quantity')->default(0);
            $table->integer('status')->comment("1=pending, 2=delivered, 3=partially delivered");
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
        Schema::dropIfExists('cadet_health_drugs');
    }
}
