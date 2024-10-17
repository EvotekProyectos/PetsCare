<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\VaccineCertificate;
use App\Models\User;

class VaccineCertificatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("ver panel cartilla vacunación");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VaccineCertificate $vaccineCertificate): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("crear cartilla vacunación");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VaccineCertificate $vaccineCertificate): bool
    {
        return $user->can("editar cartilla vacunación");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VaccineCertificate $vaccineCertificate): bool
    {
        return $user->can("eliminar cartilla vacunación");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VaccineCertificate $vaccineCertificate): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VaccineCertificate $vaccineCertificate): bool
    {
        //
    }
}
