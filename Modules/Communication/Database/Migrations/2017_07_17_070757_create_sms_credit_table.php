<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_credit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institution_id');
            $table->integer('campus_id');
            $table->integer('sms_amount');
            $table->string('status')->nullable();
            $table->integer('sms_type')->nullable();
            $table->string('submitted_by')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('accepted_date')->nullable();
            $table->string('comment')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('payable')->default('0');
            $table->integer('payment_status')->default('0');
            $table->integer('month')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_credit');
    }
}
