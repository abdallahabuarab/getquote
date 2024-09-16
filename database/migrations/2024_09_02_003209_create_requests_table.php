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
        Schema::create('requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->binary('request_ip_address', 16);
            $table->string('request_ip_city', 32);
            $table->string('request_ip_region', 32);
            $table->string('request_ip_country', 32);
            $table->string('request_ip_timezone', 32);
            $table->string('request_device', 20);
            $table->string('request_os', 20);
            $table->string('request_street_number', 20);
            $table->string('request_route', 32);
            $table->string('request_locality', 32);
            $table->char('request_administrative_area_level_1', 2);
            $table->unsignedInteger('request_zipcode');
            $table->string('request_cross_street', 32);
            $table->string('request_location_name', 50);
            $table->unsignedTinyInteger('request_location_type_id');
            $table->decimal('request_longitude', 9, 6);
            $table->decimal('request_latitude', 8, 6);
            $table->unsignedTinyInteger('request_class');
            $table->unsignedTinyInteger('request_service');
            $table->dateTime('request_datetime');
            $table->enum('request_priority', ['normal', 'low', 'high']);
            $table->enum('request_address_source', ['GAPI', 'Manual']);
            $table->timestamps();

            $table->foreign('request_zipcode')->references('zipcode')->on('zipcode_reference')->onDelete('cascade');
            $table->foreign('request_location_type_id')->references('location_type_id')->on('location_types')->onDelete('cascade');
            $table->foreign('request_class')->references('class_id')->on('classes')->onDelete('cascade');
            $table->foreign('request_service')->references('service_id')->on('services')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
