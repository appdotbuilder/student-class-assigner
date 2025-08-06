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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique()->comment('Student identification number');
            $table->string('first_name')->comment('Student first name');
            $table->string('last_name')->comment('Student last name');
            $table->date('date_of_birth')->comment('Student date of birth');
            $table->enum('gender', ['male', 'female'])->comment('Student gender');
            $table->string('grade')->comment('Current grade level');
            $table->text('notes')->nullable()->comment('Additional notes about student');
            $table->enum('status', ['active', 'inactive', 'transferred'])->default('active')->comment('Student status');
            $table->timestamps();
            
            $table->index(['first_name', 'last_name']);
            $table->index('grade');
            $table->index('gender');
            $table->index(['status', 'grade']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};