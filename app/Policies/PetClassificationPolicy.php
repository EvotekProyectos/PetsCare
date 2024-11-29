<?php

namespace App\Policies;

use App\Models\PetClassification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PetClassificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel clasificacion mascotas");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PetClassification $petClassification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear clasificacion mascotas");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PetClassification $petClassification): bool
    {
        return $user->can("editar clasificacion mascotas");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PetClassification $petClassification): bool
    {
        return $user->can("eliminar clasificacion mascotas");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PetClassification $petClassification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PetClassification $petClassification): bool
    {
        return false;
    }
}
