<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('email');
            $table->string('address_text');

            //invoice data
            $table->json('invoice_data')->nullable();
            $table->string('invoice_type')->nullable();

            $table->integer('amount'); // grosze
            $table->string('currency', 3)->default('PLN');

            $table->string('payment_provider')->nullable();
            $table->string('external_payment_id')->nullable();

            $table->string('status');

            $table->timestamps();

            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
