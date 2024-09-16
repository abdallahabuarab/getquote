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
        Schema::create('pictures', function (Blueprint $table) {
            $table->id('picture_id'); // Primary key
            $table->unsignedBigInteger('request_id');
            $table->string('picture_name', 20);
            $table->integer('picture_size');
            $table->string('picture_location', 512);
            $table->decimal('upload_longitude', 9, 6)->nullable();
            $table->decimal('upload_latitude', 8, 6)->nullable();
            $table->dateTime('upload_time');
            $table->enum('uploaded_by', ['customer', 'provider', 'TN', 'other']);
            $table->binary('upload_ip', 16)->nullable();

            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pictures');
    }
};
