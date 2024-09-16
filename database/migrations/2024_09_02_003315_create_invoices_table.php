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
        Schema::create('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id');
            $table->unsignedTinyInteger('item_id');
            $table->integer('quantity');
            $table->decimal('item_cost', 8, 2);
            $table->timestamps();

            $table->primary(['request_id', 'item_id']);

            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('invoice_items')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
