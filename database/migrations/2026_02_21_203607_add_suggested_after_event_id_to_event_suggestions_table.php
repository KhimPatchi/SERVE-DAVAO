<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_suggestions', function (Blueprint $table) {
            $table->unsignedBigInteger('suggested_after_event_id')->nullable()->after('organizer_notes');
        });
    }

    public function down(): void
    {
        Schema::table('event_suggestions', function (Blueprint $table) {
            $table->dropColumn('suggested_after_event_id');
        });
    }
};
