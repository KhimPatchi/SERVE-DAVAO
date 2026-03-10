<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add the new columns
        Schema::table('event_volunteers', function (Blueprint $table) {
            $table->timestamp('check_in_time')->nullable()->after('hours_volunteered');
        });

        // Step 2: Alter the enum to add 'no-show'
        // MySQL requires a CHANGE COLUMN to update an enum
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE event_volunteers MODIFY COLUMN status ENUM('registered', 'attended', 'no-show', 'cancelled') NOT NULL DEFAULT 'registered'");
        }
    }

    public function down(): void
    {
        // Revert enum first (remove 'no-show')
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE event_volunteers MODIFY COLUMN status ENUM('registered', 'attended', 'cancelled') NOT NULL DEFAULT 'registered'");
        }

        Schema::table('event_volunteers', function (Blueprint $table) {
            $table->dropColumn('check_in_time');
        });
    }
};
