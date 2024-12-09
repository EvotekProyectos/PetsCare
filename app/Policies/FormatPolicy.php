<?php

namespace App\Policies;

use App\Models\Format;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FormatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("Ver panel de Formatos");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Format $format): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user ->can("Crear formato");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Format $format): bool
    {
        return $user ->can("Editar formato");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Format $format): bool
    {
        return $user ->can("Eliminar formato");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Format $format): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Format $format): bool
    {
        return false;
    }
}
