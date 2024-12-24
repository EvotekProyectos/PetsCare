<?php

namespace App\Policies;

use App\Models\TagType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel tipo de placas para cremación");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TagType $tagType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear tipo de placas para cremación");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TagType $tagType): bool
    {
        return $user->can("editar tipo de placas para cremación");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TagType $tagType): bool
    {
        return $user->can("eliminar tipo de placas para cremación");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TagType $tagType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TagType $tagType): bool
    {
        return false;
    }
}
