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
        Schema::table('provider_responses', function (Blueprint $table) {
            $table->dropForeign(['reason_id']);

            $table->dropColumn('reason_id');

            $table->string('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_responses', function (Blueprint $table) {
            $table->dropColumn('reason');

            $table->unsignedTinyInteger('reason_id')->nullable();
            $table->foreign('reason_id')->references('reason_id')->on('drop_reasons')->onDelete('cascade');
        });
    }
};
