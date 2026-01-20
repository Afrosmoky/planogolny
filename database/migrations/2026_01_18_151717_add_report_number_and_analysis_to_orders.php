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
        Schema::table('orders', function (Blueprint $table) {
            // powiązanie z analysis
            $table->unsignedBigInteger('analysis_id')->after('id');

            // numer raportu widoczny dla klienta
            $table->string('report_number')->unique()->after('analysis_id');

            $table->foreign('analysis_id')
                ->references('id')
                ->on('analyses')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['analysis_id']);
            $table->dropColumn(['analysis_id', 'report_number']);
        });
    }
};
