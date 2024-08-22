<?php

namespace App\Policies;

use App\Models\Reason;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReasonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel motivos");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reason $reason): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear motivos");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can("editar motivos");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can("eliminar motivos");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reason $reason): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reason $reason): bool
    {
        //
    }
}
