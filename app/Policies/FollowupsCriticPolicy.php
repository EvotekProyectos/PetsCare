<?php

namespace App\Policies;

use App\Models\FollowupsCritic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FollowupsCriticPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel seguimientos de criticos");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FollowupsCritic $followupsCritic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear seguimientos de criticos");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FollowupsCritic $followupsCritic): bool
    {
        return $user->can("editar seguimientos de criticos");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FollowupsCritic $followupsCritic): bool
    {
        return $user->can("eliminar seguimientos de criticos");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FollowupsCritic $followupsCritic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FollowupsCritic $followupsCritic): bool
    {
        return false;
    }
}
