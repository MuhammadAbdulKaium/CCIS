<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_promotions', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('previous_department')->nullable();
            $table->integer('previous_designation')->nullable();
            $table->integer('previous_category')->nullable();
            $table->integer('department')->nullable();
            $table->integer('designation')->nullable();
            $table->integer('category')->nullable();
            $table->string('promotion_board')->nullable();
            $table->longText('reasoning')->nullable();
            $table->longText('board_remarks')->nullable();
            $table->enum('status',['approved','pending','rejected'])->default('pending');
            $table->integer('campus');
            $table->integer('institute');
            $table->integer('prev_campus');
            $table->integer('prev_institute');
            $table->integer('created_by');
            $table->integer('approved_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->date('last_promotion_date')->nullable();
            $table->date('promotion_date')->nullable();
            $table->timestamps();
        });
    }
    /* protected $fillable = [
        'employee_id',
        'previous_dept',
        'previous_designation',
        'previous_category',
        'dept',
        'designation',
        'category',
        'status',
        'campus',
        'institute','prev_campus','current_institute'
    ];
    public function employee(){
        return $this->belongsTo(EmployeeInformation::class,'employee_id','id');
    }*/

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_promotions');
    }
}
