<?php

namespace App\Policies;

use App\Models\AttentionStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttentionStatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver panel estados de atención');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AttentionStatus $attentionStatus): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear estados de atención');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AttentionStatus $attentionStatus): bool
    {
        return $user->can('editar estados de atención');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AttentionStatus $attentionStatus): bool
    {
        return $user->can('eliminar estados de atención');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AttentionStatus $attentionStatus): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AttentionStatus $attentionStatus): bool
    {
        //
    }
}
