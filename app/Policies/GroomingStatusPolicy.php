<?php

namespace App\Policies;

use App\Models\GroomingStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroomingStatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver panel estados de grooming');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GroomingStatus $groomingStatus): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear estados de grooming');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GroomingStatus $groomingStatus): bool
    {
        return $user->can('editar estados de grooming');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GroomingStatus $groomingStatus): bool
    {
        return $user->can('eliminar estados de grooming');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GroomingStatus $groomingStatus): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GroomingStatus $groomingStatus): bool
    {
        return false;
    }
}
