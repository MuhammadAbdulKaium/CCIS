<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingInstituteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_institute', function (Blueprint $table) {
            $table->increments('id');
            $table->string('institute_name')->nullable();
            $table->string('institute_alias')->nullable();
            $table->integer('institute_serial')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('eiin_code')->nullable();
            $table->string('center_code')->nullable();
            $table->string('institute_code')->nullable();
            $table->string('upazila_code')->nullable();
            $table->string('zilla_code')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('bn_institute_name')->nullable();
            $table->string('bn_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('setting_institute');
    }
}
