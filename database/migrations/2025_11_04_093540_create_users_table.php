<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('role', ['user', 'admin'])->default('user'); // ONLY 2 ROLES
            $table->rememberToken();
            $table->timestamps();
            
            $table->index(['role', 'email']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};