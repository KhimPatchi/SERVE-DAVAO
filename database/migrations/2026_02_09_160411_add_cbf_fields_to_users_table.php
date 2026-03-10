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
            $table->text('preferences')->nullable()->after('avatar');
            $table->text('interests')->nullable()->after('preferences');
            $table->string('experience_level')->default('beginner')->after('interests');
            $table->text('availability')->nullable()->after('experience_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['preferences', 'interests', 'experience_level', 'availability']);
        });
    }
};
