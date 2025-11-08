<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('event_volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['registered', 'attended', 'cancelled'])->default('registered');
            $table->decimal('hours_volunteered', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['event_id', 'volunteer_id']);
            $table->index(['volunteer_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_volunteers');
    }
};