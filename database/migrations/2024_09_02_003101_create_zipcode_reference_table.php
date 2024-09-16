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
        Schema::create('zipcode_reference', function (Blueprint $table) {
            $table->unsignedInteger('zipcode')->primary();
            $table->string('city', 32);
            $table->char('state', 2);
            $table->integer('population')->nullable();
            $table->integer('density')->nullable();
            $table->string('county', 32)->nullable();
            $table->string('timezone', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zipcode_reference');
    }
};
