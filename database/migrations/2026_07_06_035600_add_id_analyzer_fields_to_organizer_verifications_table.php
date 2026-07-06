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
        Schema::table('organizer_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('organizer_verifications', 'selfie_path')) {
                $table->string('selfie_path')->nullable()->after('identification_document_path');
            }
            if (!Schema::hasColumn('organizer_verifications', 'document_type')) {
                $table->string('document_type')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('organizer_verifications', 'verification_score')) {
                $table->float('verification_score')->nullable()->after('document_type');
            }
            if (!Schema::hasColumn('organizer_verifications', 'face_match_score')) {
                $table->float('face_match_score')->nullable()->after('verification_score');
            }
            if (!Schema::hasColumn('organizer_verifications', 'issuing_country')) {
                $table->string('issuing_country')->nullable()->after('face_match_score');
            }
            if (!Schema::hasColumn('organizer_verifications', 'verification_data')) {
                $table->longText('verification_data')->nullable()->after('issuing_country');
            }
            if (!Schema::hasColumn('organizer_verifications', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verification_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizer_verifications', function (Blueprint $table) {
            $table->dropColumn([
                'selfie_path',
                'document_type',
                'verification_score',
                'face_match_score',
                'issuing_country',
                'verification_data',
                'verified_at'
            ]);
        });
    }
};
