<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Worksheet;
use Illuminate\Auth\Access\Response;

class WorksheetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read worksheets');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Worksheet $worksheet): bool
    {
        return $user->can('read worksheets');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create worksheets');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Worksheet $worksheet): bool
    {
        return $user->can('update worksheets');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Worksheet $worksheet): bool
    {
        return $user->can('delete worksheets');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Worksheet $worksheet): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Worksheet $worksheet): bool
    {
        return false;
    }
}
