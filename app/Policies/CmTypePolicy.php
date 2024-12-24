<?php

namespace App\Policies;

use App\Models\CmType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CmTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel C.M");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CmType $cmType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear C.M");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CmType $cmType): bool
    {
        return $user->can("editar C.M");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CmType $cmType): bool
    {
        return $user->can("eliminar C.M");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CmType $cmType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CmType $cmType): bool
    {
        return false;
    }
}
