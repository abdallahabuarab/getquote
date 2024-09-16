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
        Schema::create('provider_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id');
            $table->unsignedTinyInteger('dayofweek');
            $table->integer('start_time')->nullable();
            $table->integer('close_time')->nullable();
            $table->enum('open_day', ['yes', 'no']);

            $table->primary(['provider_id', 'dayofweek']);

            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
            $table->foreign('dayofweek')->references('dayofweek')->on('weekdays')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_schedules');
    }
};
