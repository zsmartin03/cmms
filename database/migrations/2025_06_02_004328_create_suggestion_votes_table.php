<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggestion_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suggestion_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('vote_type', ['up', 'down']);
            $table->timestamps();

            // Egy felhasználó csak egyszer szavazhat egy javaslatra
            $table->unique(['suggestion_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestion_votes');
    }
};
