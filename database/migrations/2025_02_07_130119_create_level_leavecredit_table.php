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
        Schema::create('level_leavecredit', function (Blueprint $table) {
            $table->id();
            $table->integer('leave_id')->nullable();
            $table->integer('emp_class_id')->nullable();
            $table->string('description')->nullable();
            $table->double('credits')->nullable();
            $table->integer('is_no_limit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_leavecredit');
    }
};
