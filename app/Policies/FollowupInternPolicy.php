<?php

namespace App\Policies;

use App\Models\FollowupIntern;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FollowupInternPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("Ver panel pase de guardia internos");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FollowupIntern $followupIntern): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("Crear pase de guardia interno");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FollowupIntern $followupIntern): bool
    {
        return $user->can("Editar pase de guardia interno");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FollowupIntern $followupIntern): bool
    {
        return $user->can("Eliminar pase de guardia interno");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FollowupIntern $followupIntern): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FollowupIntern $followupIntern): bool
    {
        return false;
    }
}
