<?php

namespace App\Policies;

use App\Models\CoverArea;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoverAreaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver panel areas a cubrir');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CoverArea $coverArea): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear areas a cubrir');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CoverArea $coverArea): bool
    {
        return $user->can('editar areas a cubrir');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CoverArea $coverArea): bool
    {
        return $user->can('eliminar areas a cubrir');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CoverArea $coverArea): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CoverArea $coverArea): bool
    {
        return false;
    }
}
