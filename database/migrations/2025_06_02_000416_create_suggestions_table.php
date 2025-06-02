<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SuggestionType;
use App\Enums\SuggestionStatus;
use App\Enums\SuggestionPriority;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');

            $table->string('type')->default(SuggestionType::IDEA->value);
            $table->string('status')->default(SuggestionStatus::SUBMITTED->value);
            $table->tinyInteger('priority')->default(SuggestionPriority::NORMAL->value);

            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');

            $table->string('category')->nullable();
            $table->string('location')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index(['category']);
            $table->index(['priority', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
