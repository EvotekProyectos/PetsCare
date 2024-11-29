<?php

namespace App\Policies;

use App\Models\ReproductiveStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReproductiveStatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel estados reproductivos");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReproductiveStatus $reproductiveStatus): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear estados reproductivos");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReproductiveStatus $reproductiveStatus): bool
    {
        return $user->can("editar estados reproductivos");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReproductiveStatus $reproductiveStatus): bool
    {
        return $user->can("eliminar estados reproductivos");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReproductiveStatus $reproductiveStatus): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReproductiveStatus $reproductiveStatus): bool
    {
        return false;
    }
}
