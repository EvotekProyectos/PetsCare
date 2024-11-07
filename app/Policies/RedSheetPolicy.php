<?php

namespace App\Policies;

use App\Models\RedSheet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RedSheetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel hoja roja");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RedSheet $redSheet): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear hoja roja");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RedSheet $redSheet): bool
    {
        return $user->can("editar hoja roja");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RedSheet $redSheet): bool
    {
        return $user->can("eliminar hoja roja");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RedSheet $redSheet): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RedSheet $redSheet): bool
    {
        return false;
    }
}
