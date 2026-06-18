<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Safe re-run guard
        if (!Schema::hasColumn('organizer_verifications', 'liveness_score')) {
            Schema::table('organizer_verifications', function (Blueprint $table) {
                $table->float('liveness_score')->nullable()->after('face_match_score');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('organizer_verifications', 'liveness_score')) {
            Schema::table('organizer_verifications', function (Blueprint $table) {
                $table->dropColumn('liveness_score');
            });
        }
    }
};
