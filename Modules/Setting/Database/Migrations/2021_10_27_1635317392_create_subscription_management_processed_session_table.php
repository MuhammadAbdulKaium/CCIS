<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionManagementProcessedSessionTable extends Migration
{
    public function up()
    {
        Schema::create('subscription_management_processed_session', function (Blueprint $table) {

		$table->bigIncrements('id');
		$table->integer('subscription_management_transactions_id')->unsigned();
		$table->decimal('total_amount',8,2)->nullable();
		$table->decimal('accepted_amount',8,2)->nullable();
		$table->decimal('total_sms_price',8,2)->nullable();
		$table->decimal('accepted_sms_price',8,2)->nullable();
		$table->decimal('old_dues',8,2)->nullable();
		$table->decimal('monthly_total_charge',8,2)->nullable();
		$table->decimal('paid_amount',8,2)->nullable();
		$table->decimal('new_dues',8,2)->nullable();
		$table->string('status',100)->nullable();
		$table->string('sms',100)->nullable();
		$table->string('email',100)->nullable();
		$table->string('invoice',100)->nullable();
		$table->timestamp('created_at')->nullable();
		$table->timestamp('updated_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_management_processed_session');
    }
}