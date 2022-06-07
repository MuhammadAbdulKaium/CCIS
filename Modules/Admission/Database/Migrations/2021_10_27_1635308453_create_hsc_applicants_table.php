<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHscApplicantsTable extends Migration
{
    public function up()
    {
        Schema::create('hsc_applicants', function (Blueprint $table) {

		$table->id();
		$table->string('username');
		$table->string('password');
		$table->string('password_rand',45)->nullable()->default('NULL');
		$table->integer('a_no')->unsigned()->nullable();
		$table->tinyInteger('a_status')->default('0');
		$table->tinyInteger('p_status')->default('0');

		$table->integer('batch')->unsigned()->nullable();
		$table->integer('level')->unsigned()->nullable();
		$table->integer('year')->unsigned()->nullable();
		$table->string('s_name')->nullable();
		$table->string('s_name_bn')->nullable();
		$table->string('s_nid',17)->nullable();
		$table->tinyInteger('gender')->nullable();
		$table->date('b_date')->nullable();
		$table->string('b_group',10)->nullable();
		$table->string('s_mobile')->nullable();
		$table->integer('nationality')->unsigned()->nullable();
		$table->integer('religion')->unsigned()->nullable();
            $table->string('f_name')->nullable()->default('NULL');
            $table->string('f_name_bn')->nullable()->default('NULL');
            $table->string('f_occupation')->nullable()->default('NULL');
            $table->string('f_education')->nullable()->default('NULL');
            $table->string('f_income')->nullable()->default('NULL');
            $table->string('f_income_bn')->nullable()->default('NULL');
            $table->string('f_mobile')->nullable()->default('NULL');
            $table->string('f_nid')->nullable()->default('NULL');
            $table->string('m_name')->nullable()->default('NULL');
            $table->string('m_name_bn')->nullable()->default('NULL');
            $table->string('m_occupation')->nullable()->default('NULL');
            $table->string('m_education')->nullable()->default('NULL');
            $table->string('m_mobile')->nullable()->default('NULL');
            $table->string('m_nid',17)->nullable()->default('NULL');
            $table->integer('a_thana')->unsigned()->nullable();
            $table->integer('a_zilla')->unsigned()->nullable();
            $table->string('post')->nullable()->default('NULL');
            $table->string('vill')->nullable()->default('NULL');
            $table->string('address')->nullable()->default('NULL');
            $table->string('exam_name',50)->nullable();
            $table->string('exam_board',20)->nullable();
            $table->string('exam_year',4)->nullable();
            $table->string('exam_reg',25)->nullable();
            $table->string('exam_roll',20)->nullable();
            $table->string('exam_session',10)->nullable();
            $table->string('exam_gpa',10)->nullable();

            $table->string('exam_institute')->nullable();
            $table->integer('campus_id')->unsigned()->nullable();
            $table->integer('institute_id')->unsigned()->nullable();
           $table->timestamps();
           $table->softDeletes();
            $table->float('amount')->nullable();
            $table->string('transaction_id',45)->nullable();





        });
    }

    public function down()
    {
        Schema::dropIfExists('hsc_applicants');
    }
}