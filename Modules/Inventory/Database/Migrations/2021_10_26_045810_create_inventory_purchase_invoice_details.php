<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPurchaseInvoiceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_invoice_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pur_invoice_id');
            $table->integer('item_id');
            $table->decimal('qty',18,6)->nullable();
            $table->decimal('rate',18,6)->nullable();
            $table->decimal('total_amount',18,6)->nullable();
            $table->decimal('vat_per',18,6)->nullable();
            $table->decimal('vat_amount',18,6)->nullable();
            $table->decimal('discount',18,6)->nullable();
            $table->decimal('net_total',18,6)->nullable();
            $table->string('vat_type',15)->nullable();
            $table->string('reference_type',100)->nullable();
            $table->tinyInteger('ref_use')->default(0)->nullable()->comment('0=No, 1= partial use, 3=used');
            $table->integer('reference_id')->comment('voucher id');
            $table->integer('reference_details_id')->comment('voucher details id');
            $table->tinyInteger('approval_level')->default(1)->nullable()->comment('current approval step');
            $table->string('remarks')->nullable();
            $table->tinyInteger('status')->default(0)->nullable()->comment('0=pending,1=apprroved,2=partial approve,3=reject');
            $table->integer('institute_id');
            $table->integer('campus_id');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_purchase_invoice_details');
    }
}
