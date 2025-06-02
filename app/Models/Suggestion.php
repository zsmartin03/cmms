<?php

namespace App\Models;

use App\Enums\SuggestionType;
use App\Enums\SuggestionStatus;
use App\Enums\SuggestionPriority;
use App\Enums\SuggestionCategory;
use Coolsam\NestedComments\Concerns\HasComments;
use Coolsam\NestedComments\Concerns\HasReactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suggestion extends Model
{
    use HasFactory, SoftDeletes, HasComments, HasReactions;

    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'priority',
        'author_id',
        'category',
        'location',
        'assigned_to',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'type' => SuggestionType::class,
        'status' => SuggestionStatus::class,
        'priority' => SuggestionPriority::class,
        'category' => SuggestionCategory::class,
        'resolved_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(SuggestionVote::class);
    }

    public function getSuggestionNumberAttribute(): string
    {
        return 'S-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getVotesUpAttribute(): int
    {
        return $this->votes()->where('vote_type', 'up')->count();
    }

    public function getVotesDownAttribute(): int
    {
        return $this->votes()->where('vote_type', 'down')->count();
    }

    public function getTotalVotesAttribute(): int
    {
        return $this->votes_up + $this->votes_down;
    }

    public function getVotePercentageAttribute(): float
    {
        $total = $this->total_votes;
        return $total > 0 ? round(($this->votes_up / $total) * 100, 1) : 0;
    }

    public function hasUserVoted(User $user): bool
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function getUserVoteType(User $user): ?string
    {
        $vote = $this->votes()->where('user_id', $user->id)->first();
        return $vote ? $vote->vote_type : null;
    }

    public function vote(User $user, string $voteType): bool
    {
        // Ellenőrizzük, hogy létező szavazat típus-e
        if (!in_array($voteType, ['up', 'down'])) {
            return false;
        }

        // Töröljük a meglévő szavazatot (ha van)
        $this->votes()->where('user_id', $user->id)->delete();

        // Új szavazat létrehozása
        $this->votes()->create([
            'user_id' => $user->id,
            'vote_type' => $voteType
        ]);

        return true;
    }

    public function removeVote(User $user): bool
    {
        return $this->votes()->where('user_id', $user->id)->delete() > 0;
    }

    public function scopeByType($query, SuggestionType $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, SuggestionStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            SuggestionStatus::SUBMITTED,
            SuggestionStatus::UNDER_REVIEW,
            SuggestionStatus::IN_PROGRESS
        ]);
    }
}
