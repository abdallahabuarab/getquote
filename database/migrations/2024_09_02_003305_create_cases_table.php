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
        Schema::create('cases', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id');
            $table->enum('inside_garage', ['no', 'yes', 'unknown'])->nullable();
            $table->decimal('garage_clearance', 6, 2)->nullable();
            $table->tinyInteger('garage_floor')->nullable();
            $table->enum('dangerous_location', ['no', 'yes', 'unknown'])->nullable();
            $table->enum('vehicle_starts', ['no', 'yes', 'unknown'])->nullable();
            $table->enum('flat_tire', ['no', 'yes', 'unknown'])->nullable();
            $table->tinyInteger('tires_flat')->nullable();
            $table->enum('missing_wheels', ['no', 'yes', 'unknown'])->nullable();
            $table->tinyInteger('wheels_missing')->nullable();
            $table->enum('broken_axle', ['no', 'yes', 'unknown'])->nullable();
            $table->enum('key_present', ['no', 'yes', 'unknown'])->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('provider_notes')->nullable();
            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
