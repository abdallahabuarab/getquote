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
            Schema::create('zipcode_coverage', function (Blueprint $table) {
                $table->unsignedInteger('zipcode');
                $table->tinyInteger('rank');
                $table->unsignedBigInteger('provider_id');

                $table->primary(['provider_id', 'zipcode', 'rank']);

                $table->foreign('zipcode')->references('zipcode')->on('zipcode_reference')->onDelete('cascade');
                $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
            });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zipcode_coverage');
    }
};
