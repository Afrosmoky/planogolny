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
        Schema::create('analyses_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('analyses_id')
                ->constrained('analyses')
                ->cascadeOnDelete()
                ->unique();

            $table->json('surroundings');     // DTO → array
            $table->json('legal_constraints');// DTO → array
            $table->json('gus_data')->nullable();

            $table->json('meta')->nullable();
            // np. źródła, fallbacki, warnings

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyses_results');
    }
};
