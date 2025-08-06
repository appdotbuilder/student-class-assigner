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
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Academic year name (e.g., 2024-2025)');
            $table->date('start_date')->comment('Academic year start date');
            $table->date('end_date')->comment('Academic year end date');
            $table->boolean('is_active')->default(false)->comment('Current active academic year');
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};