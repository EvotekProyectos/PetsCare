<?php

namespace App\Policies;

use App\Models\ControlDate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ControlDatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver panel citas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ControlDate $controlDate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear citas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ControlDate $controlDate): bool
    {
        return $user->can('editar citas');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ControlDate $controlDate): bool
    {
        return $user->can('eliminar citas');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ControlDate $controlDate): bool
    {
         return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ControlDate $controlDate): bool
    {
        return false;
    }
}
