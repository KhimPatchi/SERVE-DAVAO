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
        Schema::create('event_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('overall_rating')->unsigned()->comment('1-5 stars');
            $table->tinyInteger('organization_rating')->unsigned()->nullable()->comment('1-5 stars');
            $table->tinyInteger('impact_rating')->unsigned()->nullable()->comment('1-5 stars');
            $table->text('comment')->nullable();
            $table->boolean('would_recommend')->default(true);
            $table->timestamps();
            
            // Ensure one feedback per volunteer per event
            $table->unique(['event_id', 'volunteer_id']);
            
            // Indexes for faster queries
            $table->index('overall_rating');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_feedback');
    }
};
