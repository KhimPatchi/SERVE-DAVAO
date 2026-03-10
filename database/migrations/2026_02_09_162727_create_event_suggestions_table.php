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
        Schema::create('event_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category')->nullable();
            $table->string('location')->nullable();
            $table->integer('votes')->default(0);
            $table->enum('status', ['pending', 'reviewed', 'implemented', 'rejected'])->default('pending');
            $table->text('organizer_notes')->nullable();
            $table->timestamps();
            
            // Indexes for filtering and sorting
            $table->index('status');
            $table->index('votes');
            $table->index('category');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_suggestions');
    }
};
