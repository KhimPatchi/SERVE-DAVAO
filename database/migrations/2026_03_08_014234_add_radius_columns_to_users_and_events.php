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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('preferred_radius', 8, 2)->nullable()->default(15.00)->after('longitude');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->decimal('target_radius', 8, 2)->nullable()->default(15.00)->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_radius');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('target_radius');
        });
    }
};
