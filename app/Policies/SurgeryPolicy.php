<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Surgery;
use App\Models\User;

class SurgeryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel cirugías");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Surgery $surgery): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear cirugía");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Surgery $surgery): bool
    {
        return $user->can("editar cirugía");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Surgery $surgery): bool
    {
        return $user ->can("eliminar cirugía");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Surgery $surgery): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Surgery $surgery): bool
    {
        return false;
    }
}
