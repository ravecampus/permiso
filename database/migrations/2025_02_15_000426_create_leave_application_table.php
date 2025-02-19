<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_application', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->integer("leave_id")->nullable();
            $table->integer("initial_appr_id")->nullable();
            $table->date("initial_appr_date")->nullable();
            $table->integer("final_appr_id")->nullable();
            $table->date("final_appr_date")->nullable();
            $table->integer("school_year_id")->nullable();
            $table->integer("emp_class_id")->nullable();
            $table->integer("leave_credit_id")->nullable();
            $table->date("date_apply")->nullable();
            $table->date("from")->nullable();
            $table->integer("from_ext")->nullable();
            $table->date("until")->nullable();
            $table->integer("until_ext")->nullable();
            $table->double("number_of_day")->nullable();
            $table->string("cause")->nullable();
            $table->string("remarks")->nullable();
            $table->integer("status")->nullable();
            $table->date("disapproved_date")->nullable();
            $table->longText('sick_attach')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_application');
    }
};
