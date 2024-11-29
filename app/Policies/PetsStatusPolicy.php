<?php

namespace App\Policies;

use App\Models\PetsStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PetsStatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel estados mascotas");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PetsStatus $petsStatus): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear estados mascotas");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PetsStatus $petsStatus): bool
    {
        return $user->can("editar estados mascotas");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PetsStatus $petsStatus): bool
    {
        return $user->can("eliminar estados mascotas");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PetsStatus $petsStatus): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PetsStatus $petsStatus): bool
    {
        return false;
    }
}
