<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuggestionVote extends Model
{
    protected $fillable = [
        'suggestion_id',
        'user_id',
        'vote_type'
    ];

    public function suggestion(): BelongsTo
    {
        return $this->belongsTo(Suggestion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
