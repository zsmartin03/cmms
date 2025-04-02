<?php

namespace App\Policies;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read roles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $roles): bool
    {
        return $user->can('read roles');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create roles');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $roles): bool
    {
        return $user->can('update roles');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $roles): bool
    {
        return $user->can('delete roles');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    /** 
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
