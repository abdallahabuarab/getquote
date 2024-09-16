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
        Schema::create('provider_credentials', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id');
            $table->string('login_name', 12);
            $table->char('password', 60);
            $table->timestamps();

            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_credentials');
    }
};
