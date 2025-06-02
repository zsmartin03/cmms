<?php

namespace App\Policies;

use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SuggestionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read suggestions');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Suggestion $suggestion): bool
    {
        return $user->can('read suggestions');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create suggestions');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Suggestion $suggestion): bool
    {
        // Admin és repairer mindent szerkeszthet
        if ($user->hasAnyRole(['admin', 'repairer'])) {
            return $user->can('update suggestions');
        }

        // Operator csak a saját javaslatait szerkesztheti
        if ($user->hasRole('operator')) {
            return $user->can('update suggestions') && $suggestion->author_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Suggestion $suggestion): bool
    {
        // Csak admin törölhet
        if ($user->hasRole('admin')) {
            return $user->can('delete suggestions');
        }

        // Operator csak a saját javaslatait törölheti (ha még "submitted" státuszban van)
        if ($user->hasRole('operator') && $suggestion->author_id === $user->id) {
            return $user->can('delete suggestions') && $suggestion->status->value === 'submitted';
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Suggestion $suggestion): bool
    {
        return $user->hasRole('admin') && $user->can('delete suggestions');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Suggestion $suggestion): bool
    {
        return $user->hasRole('admin') && $user->can('delete suggestions');
    }
}
