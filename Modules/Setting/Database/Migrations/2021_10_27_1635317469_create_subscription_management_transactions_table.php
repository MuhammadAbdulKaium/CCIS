<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionManagementTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscription_management_transactions', function (Blueprint $table) {

		$table->bigIncrements('id');
		$table->integer('institute_billing_info_id')->unsigned();
		$table->decimal('old_dues',8,2)->nullable();
		$table->decimal('monthly_total_charge',8,2)->nullable();
		$table->decimal('paid_amount',8,2)->nullable();
		$table->decimal('new_dues',8,2)->nullable();
		$table->datetime('paid_on')->nullable();
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
        Schema::dropIfExists('subscription_management_transactions');
    }
}