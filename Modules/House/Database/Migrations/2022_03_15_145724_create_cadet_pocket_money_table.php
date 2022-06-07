<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetPocketMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_pocket_money', function (Blueprint $table) {
            $table->id();
            $table->integer('std_id');
            $table->string('account_no')->nullable();
            $table->integer('bank_branch_id')->nullable();
            $table->float('account_balance')->nullable();
            $table->float('money_in')->nullable();
            $table->float('last_allotment')->nullable();
            $table->float('total_allotment')->nullable();
            $table->float('last_expense')->nullable();
            $table->float('total_expense')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('cadet_pocket_money');
    }
}
