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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->comment('Teacher first name');
            $table->string('last_name')->comment('Teacher last name');
            $table->string('email')->unique()->comment('Teacher email address');
            $table->string('employee_id')->unique()->comment('Teacher employee ID');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Teacher status');
            $table->timestamps();
            
            $table->index(['first_name', 'last_name']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};