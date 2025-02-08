<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropPrimary();

            DB::statement('ALTER TABLE payments MODIFY COLUMN payment_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

            $table->primary('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropPrimary();

            DB::statement('ALTER TABLE payments MODIFY COLUMN payment_id BIGINT UNSIGNED NOT NULL');

            $table->primary('payment_id');});
    }
};
