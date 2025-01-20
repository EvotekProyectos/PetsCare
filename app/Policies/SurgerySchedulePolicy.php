<?php

namespace App\Policies;

use App\Models\SurgerySchedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SurgerySchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel horario de cirugías");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SurgerySchedule $surgerySchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear asignación de cirugía");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SurgerySchedule $surgerySchedule): bool
    {
        return $user->can("editar asignación de cirugía");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SurgerySchedule $surgerySchedule): bool
    {
        return $user ->can("eliminar asignación de cirugía");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SurgerySchedule $surgerySchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SurgerySchedule $surgerySchedule): bool
    {
        return false;
    }
}
