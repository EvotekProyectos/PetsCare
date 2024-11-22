<?php

namespace App\Policies;

use App\Models\FormatType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FormatTypesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("Ver panel Tipo de Formatos");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormatType $formatType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user ->can("Crear tipo de formato");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormatType $formatType): bool
    {
        return $user ->can("Editar tipo de formato");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormatType $formatType): bool
    {
        return $user ->can("Eliminar tipo de formato");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormatType $formatType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormatType $formatType): bool
    {
        return false;
    }
}
