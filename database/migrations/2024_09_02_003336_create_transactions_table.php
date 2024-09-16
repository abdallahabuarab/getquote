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
        Schema::create('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id');
            $table->decimal('payment_amount', 8, 2);
            $table->string('payment_method', 10);
            $table->enum('payment_status', ['pending', 'completed', 'failed']);
            $table->string('stripe_charge_id', 20)->nullable();
            $table->char('currency', 3);
            $table->string('transaction_confirmation', 30)->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('payment_id')->references('payment_id')->on('payments')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
