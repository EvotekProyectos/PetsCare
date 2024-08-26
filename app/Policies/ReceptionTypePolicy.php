<?php

namespace App\Policies;

use App\Models\ReceptionType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReceptionTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel tipos de recepción");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReceptionType $receptionType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear tipos de recepción");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReceptionType $receptionType): bool
    {
        return $user->can("editar tipos de recepción");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReceptionType $receptionType): bool
    {
        return $user->can("eliminar tipos de recepción");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReceptionType $receptionType): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReceptionType $receptionType): bool
    {
        //
    }
}
