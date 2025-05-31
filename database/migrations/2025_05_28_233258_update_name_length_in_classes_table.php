<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNameLengthInClassesTable extends Migration
{
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('name')->change(); // Removes the limit of 5
        });
    }

    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('name', 5)->change(); // Revert back to 5 if rolled back
        });
    }
}
