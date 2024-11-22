<?php

namespace App\Policies;

use App\Models\AppointmentService;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel servicios consultas");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AppointmentService $appointmentService): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear servicios consultas");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppointmentService $appointmentService): bool
    {
        return $user->can("editar servicios consultas");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AppointmentService $appointmentService): bool
    {
        return $user->can("eliminar servicios consultas");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppointmentService $appointmentService): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppointmentService $appointmentService): bool
    {
        return false;
    }
}
