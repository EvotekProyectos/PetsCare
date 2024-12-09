<?php

namespace App\Policies;

use App\Models\SurgeryPack;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SurgeryPackPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel paquetes cirugias");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SurgeryPack $surgeryPack): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear paquetes cirugias");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SurgeryPack $surgeryPack): bool
    {
        return $user->can("editar paquetes cirugias");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SurgeryPack $surgeryPack): bool
    {
        return $user->can("eliminar paquetes cirugias");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SurgeryPack $surgeryPack): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SurgeryPack $surgeryPack): bool
    {
        return false;
    }
}
