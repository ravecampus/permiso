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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('image')->nullable();
            $table->integer('office_id')->nullable();
            $table->integer('role')->nullable();
            $table->integer('position_id')->nullable();
            $table->integer('emp_class_id')->nullable();
            $table->longText('digital_signature')->nullable();
            $table->string('ds_size')->nullable();
            $table->string('ds_type')->nullable();
            $table->string('ds_orig_name')->nullable();
            $table->date('date_of_employment')->nullable();
            $table->string('school_id')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('deleted')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
