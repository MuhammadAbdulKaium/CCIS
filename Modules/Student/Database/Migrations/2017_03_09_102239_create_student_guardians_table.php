<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentGuardiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('type')->nullable()->comment("0 - Mother 1 - Father 2 - Sister 3 - Brother 4 - Relative 5 - Other 6 - Spouse 7 - Son 8 - Daughter");
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->integer('gender')->nullable();
            $table->text('mobile')->nullable();
            $table->string('phone')->nullable();
            //            $table->string('relation')->default('Not Available');
            $table->string('income')->nullable();
            $table->string('qualification')->nullable();
            $table->string('occupation')->nullable();
            $table->string('home_address')->nullable();
            $table->string('office_address')->nullable();
            $table->text('remarks')->nullable();
            $table->text('guardian_photo')->nullable();
            $table->text('guardian_signature')->nullable();
            $table->string('nid_number')->nullable();
            $table->text('nid_file')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->text('birth_file')->nullable();
            $table->string('tin_number')->nullable();
            $table->text('tin_file')->nullable();
            $table->string('passport_number')->nullable();
            $table->text('passport_file')->nullable();
            $table->string('dln')->nullable();
            $table->text('driving_lic_file')->nullable();
            $table->longText('institute_info')->nullable();
            $table->string('bn_fullname')->nullable();
            $table->string('bn_edu_qualification')->nullable();
            $table->string('nid')->nullable();
            $table->integer('is_guardian')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_guardians');
    }
}
