<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetPocketMoneyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_pocket_money_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('pocket_money_id');
            $table->integer('std_id');
            $table->string('account_no')->nullable();
            $table->integer('bank_branch_id')->nullable();
            $table->float('account_balance')->nullable();
            $table->float('new_account_balance')->nullable();
            $table->float('money_in')->nullable();
            $table->float('new_money_in')->nullable();
            $table->string('money_in_remarks')->nullable();
            $table->float('last_allotment')->nullable();
            $table->float('new_allotment')->nullable();
            $table->string('allotment_remarks')->nullable();
            $table->float('total_allotment')->nullable();
            $table->float('last_expense')->nullable();
            $table->float('new_expense')->nullable();
            $table->string('expense_remarks')->nullable();
            $table->float('total_expense')->nullable();
            $table->integer('status')->default(1);
            $table->enum('action_param', ['account_no', 'bank_branch', 'account_balance', 'money_in', 'allotment', 'expense', 'status']);
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
        Schema::dropIfExists('cadet_pocket_money_histories');
    }
}
