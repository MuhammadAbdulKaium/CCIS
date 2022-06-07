<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetCanteenTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_canteen_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_type');
            $table->integer('customer_id');
            $table->longText('purchase_details');
            $table->integer('total');
            $table->integer('previous_dues');
            $table->integer('amount_given');
            $table->integer('payment_for');
            $table->integer('change_to_customer');
            $table->integer('carry_forwarded_due');
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
        Schema::dropIfExists('cadet_canteen_transactions');
    }
}
