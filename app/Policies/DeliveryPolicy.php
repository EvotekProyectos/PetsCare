<?php

namespace App\Policies;

use App\Models\User;

class DeliveryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('ver panel servicios domicilio');
    }
}
