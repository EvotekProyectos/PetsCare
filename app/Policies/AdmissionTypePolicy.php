<?php

namespace App\Policies;

use App\Models\AdmissionType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdmissionTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver panel tipos de ingreso');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdmissionType $admissionType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear tipos de ingreso');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdmissionType $admissionType): bool
    {
        return $user->can('editar tipos de ingreso');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdmissionType $admissionType): bool
    {
        return $user->can('eliminar tipos de ingreso');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdmissionType $admissionType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdmissionType $admissionType): bool
    {
        return false;
    }
}
