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
        Schema::create('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id');
            $table->string('given_name', 30);
            $table->string('surname', 30);
            $table->string('email', 50);
            $table->string('phone_number', 20);
            $table->string('other_phone_number', 20)->nullable();
            $table->set('communication_preference', ['text', 'call', 'email', 'app']);
            $table->timestamps();

            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
