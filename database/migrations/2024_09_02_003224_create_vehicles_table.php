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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id');
            $table->integer('vehicle_year');
            $table->string('vehicle_make', 25);
            $table->string('vehicle_model', 25);
            $table->string('vehicle_color', 20);
            $table->string('vehicle_style', 20);
            $table->char('drive_train', 2)->nullable();
            $table->char('VIN', 17)->nullable();
            $table->string('Plate', 12)->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
