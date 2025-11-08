<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('organizer_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('organizer_verifications', 'applicant_name')) {
                $table->string('applicant_name')->after('user_id');
            }
        });
    }

    public function down()
    {
        Schema::table('organizer_verifications', function (Blueprint $table) {
            $table->dropColumn('applicant_name');
        });
    }
};