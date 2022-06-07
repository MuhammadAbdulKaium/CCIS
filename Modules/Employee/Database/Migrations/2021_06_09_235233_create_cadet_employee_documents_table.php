<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetEmployeeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_employee_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('document_type');
            $table->integer('qualification_type')->nullable();
            $table->integer('qualification_year')->nullable();
            $table->string('qualification_name')->nullable();
            $table->string('qualification_institute')->nullable();
            $table->string('qualification_institute_address')->nullable();
            $table->string('qualification_marks')->nullable();
            $table->string('qualification_group')->nullable();
            $table->string('qualification_attachment')->nullable();
            $table->date('experience_from_date')->nullable();
            $table->date('experience_to_date')->nullable();
            $table->string('experience_last_designation')->nullable();
            $table->string('experience_organization_name')->nullable();
            $table->string('experience_organization_address')->nullable();
            $table->string('experience_organization_contact_person')->nullable();
            $table->string('experience_organization_contact_email')->nullable();
            $table->string('experience_organization_contact_number')->nullable();
            $table->string('experience_attachment')->nullable();
            $table->string('document_category')->nullable();
            $table->text('document_details')->nullable();
            $table->timestamp('document_submitted_at')->nullable();
            $table->string('document_file')->nullable();
            $table->text('other_info')->nullable();
            $table->string('other_info_attachment')->nullable();
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
        Schema::dropIfExists('cadet_employee_documents');
    }
}
