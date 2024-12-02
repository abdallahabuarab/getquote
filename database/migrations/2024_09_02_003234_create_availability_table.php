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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id');
            $table->unsignedTinyInteger('class_id');
            $table->unsignedTinyInteger('service_id');
            $table->enum('availability', ['no', 'yes']);
            $table->decimal('service_price', 8, 2)->nullable();
            $table->tinyInteger('free_loaded_miles')->nullable();
            $table->tinyInteger('free_enroute_miles')->nullable();
            $table->decimal('loaded_mile_price', 8, 2)->nullable();
            $table->decimal('enroute_mile_price', 8, 2)->nullable();

            $table->primary(['provider_id', 'class_id', 'service_id']);

            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
            $table->foreign('class_id')->references('class_id')->on('classes')->onDelete('cascade');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability');
    }
};
