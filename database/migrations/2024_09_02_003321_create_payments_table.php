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
        Schema::create('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')->primary(); // Primary key
            $table->unsignedBigInteger('request_id'); // Foreign key to `requests`
            $table->decimal('request_total', 8, 2);
            $table->string('payment_method', 10);
            $table->string('brand', 20);
            $table->enum('payment_status', ['pending', 'completed', 'failed']);
            $table->unsignedSmallInteger('payment_account_last4');
            $table->string('stripe_payment_method_id', 20)->nullable();
            $table->string('billing_address', 30)->nullable();
            $table->tinyInteger('method_exp_month')->unsigned()->nullable();
            $table->tinyInteger('method_exp_year')->unsigned()->nullable();
            $table->string('payment_confirmation', 30)->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
