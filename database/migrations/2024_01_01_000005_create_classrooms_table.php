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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('grade');
            $table->integer('capacity');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['academic_year_id', 'name']);
            $table->index('grade');
            $table->index(['academic_year_id', 'grade']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};