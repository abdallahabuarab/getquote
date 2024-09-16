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
        Schema::create('providers', function (Blueprint $table) {
            $table->id('provider_id');
            $table->string('provider_name', 50);
            $table->string('provider_address', 50);
            $table->string('provider_city', 32);
            $table->char('provider_state', 2);
            $table->unsignedInteger('zipcode'); // Assume this is properly set up as a foreign key
            $table->string('provider_phone', 20);
            $table->string('provider_fax', 20)->nullable();
            $table->string('provider_email', 50);
            $table->string('contact_name', 50);
            $table->string('contact_phone', 20);
            $table->enum('is_active', ['no', 'yes']);
            $table->decimal('weekend_m', 4, 2)->nullable();
            $table->decimal('holiday_m', 4, 2)->nullable();
            $table->decimal('evening_m', 4, 2)->nullable();
            $table->unsignedTinyInteger('dispatch_method'); // Adjust the type to match the referenced column
            $table->unsignedTinyInteger('payment_distribution'); // Adjust the type to match the referenced column
            $table->enum('request_processing', ['local', 'forward']);
            $table->timestamps();

            // Adding foreign key constraints
            $table->foreign('zipcode')->references('zipcode')->on('zipcode_reference')->onDelete('cascade');
            $table->foreign('dispatch_method')->references('dispath_method_id')->on('dispatch_methods')->onDelete('cascade');
            $table->foreign('payment_distribution')->references('payment_distribution_id')->on('payment_distributions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
