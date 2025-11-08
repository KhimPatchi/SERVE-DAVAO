<?php
// database/migrations/2024_11_07_xxxxxx_add_ip_user_agent_to_audits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpUserAgentToAuditsTable extends Migration
{
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('metadata');
            $table->string('user_agent')->nullable()->after('ip_address');
        });
    }

    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent']);
        });
    }
}