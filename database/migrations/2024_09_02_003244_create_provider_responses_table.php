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
        Schema::create('provider_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('provider_id');
            $table->enum('provider_respose', ['accept', 'reject', 'expired']);
            $table->integer('eta')->nullable();
            $table->unsignedTinyInteger('reason_id')->nullable();
            $table->dateTime('provider_response_time');

            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
            $table->foreign('reason_id')->references('reason_id')->on('drop_reasons')->onDelete('cascade');


        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_responses');
    }
};
