<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('organizer_verifications')) {
            Schema::create('organizer_verifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('organization_name');
                $table->string('organization_type');
                $table->string('identification_number');
                $table->string('identification_document_path');
                $table->string('phone');
                $table->text('address');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('rejection_reason')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'status']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('organizer_verifications');
    }
};