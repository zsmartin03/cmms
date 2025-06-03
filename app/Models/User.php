<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Coolsam\NestedComments\Concerns\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, HasComments;

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@admin.hu');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'new_password',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function creators_worksheets(): HasMany
    {
        return $this->hasMany(Worksheet::class, 'creator_id');
    }
    public function repairers_worksheets(): HasMany
    {
        return $this->hasMany(Worksheet::class, 'repairer_id');
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'author_id');
    }

    public function assignedSuggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'assigned_to');
    }

    public function suggestionVotes(): HasMany
    {
        return $this->hasMany(SuggestionVote::class);
    }
}
