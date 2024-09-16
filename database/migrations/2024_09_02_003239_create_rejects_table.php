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
        Schema::create('rejects', function (Blueprint $table) {
            $table->id('reject_id'); // Primary key
            $table->binary('reject_ip_address', 16);
            $table->string('reject_ip_city', 32);
            $table->string('reject_ip_region', 32);
            $table->string('reject_ip_country', 32);
            $table->string('reject_ip_timezone', 32);
            $table->string('reject_device', 20);
            $table->string('reject_os', 20);
            $table->string('reject_street_number', 20);
            $table->string('reject_route', 32);
            $table->string('reject_locality', 32);
            $table->char('reject_administrative_area_level_1', 2);

            $table->unsignedInteger('reject_zipcode');
            $table->foreign('reject_zipcode')->references('zipcode')->on('zipcode_reference')->onDelete('cascade');

            $table->string('reject_cross_street', 32);
            $table->string('reject_location_name', 50);

            $table->unsignedTinyInteger('reject_location_type_id');
            $table->foreign('reject_location_type_id')->references('location_type_id')->on('location_types')->onDelete('cascade');

            $table->decimal('reject_longitude', 9, 6);
            $table->decimal('reject_latitude', 8, 6);

            $table->unsignedTinyInteger('reject_class');
            $table->foreign('reject_class')->references('class_id')->on('classes')->onDelete('cascade');

            $table->unsignedTinyInteger('reject_service');
            $table->foreign('reject_service')->references('service_id')->on('services')->onDelete('cascade');

            $table->dateTime('reject_datetime');
            $table->enum('reject_address_source', ['GAPI', 'Manual']);

            $table->unsignedTinyInteger('reject_reason');
            $table->foreign('reject_reason')->references('reason_id')->on('drop_reasons')->onDelete('cascade');

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rejects');
    }
};
