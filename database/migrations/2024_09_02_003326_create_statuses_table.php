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
        Schema::create('status', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('provider_id');
            $table->string('assigned_driver', 30)->nullable();
            $table->dateTime('enroute_timestamp')->nullable();
            $table->dateTime('onscene_timestamp')->nullable();
            $table->dateTime('loaded_timestamp')->nullable();
            $table->dateTime('destination_arrival_timestamp')->nullable();
            $table->dateTime('completion_timestamp')->nullable();

            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
