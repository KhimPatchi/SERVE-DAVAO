<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->dateTime('date');
            $table->string('location');
            $table->integer('required_volunteers');
            $table->integer('current_volunteers')->default(0);
            $table->string('skills_required')->nullable();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'rejected', 'cancelled', 'completed'])->default('pending');
            $table->timestamps();
            
            $table->index(['status', 'date']);
            $table->index('organizer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};