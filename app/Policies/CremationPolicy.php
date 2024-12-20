<?php

namespace App\Policies;

use App\Models\Cremation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CremationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel cremaciones");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cremation $cremation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear cremaciones");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cremation $cremation): bool
    {
        return $user->can("editar cremaciones");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cremation $cremation): bool
    {
        return $user->can("eliminar cremaciones");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cremation $cremation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cremation $cremation): bool
    {
        return false;
    }
}
