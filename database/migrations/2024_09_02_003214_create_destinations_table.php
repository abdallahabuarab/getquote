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
        Schema::create('destinations', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id'); // Make sure the data type matches
            $table->string('business_name', 32)->nullable();
            $table->string('destination_street_number', 20);
            $table->string('destination_route', 32);
            $table->string('destination_locality', 32);
            $table->char('destination_administrative_area_level_1', 2);
            $table->unsignedInteger('destination_zipcode');
            $table->string('destination_cross_street', 32)->nullable();
            $table->string('destination_name', 50);
            $table->unsignedTinyInteger('destination_location_type_id');
            $table->decimal('destination_longitude', 9, 6);
            $table->decimal('destination_latitude', 8, 6);
            $table->enum('destination_address_source', ['GAPI', 'Manual']);
            $table->string('destination_note', 360)->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
            $table->foreign('destination_zipcode')->references('zipcode')->on('zipcode_reference')->onDelete('cascade');
            $table->foreign('destination_location_type_id')->references('location_type_id')->on('location_types')->onDelete('cascade');


        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
