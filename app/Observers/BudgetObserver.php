<?php

namespace App\Observers;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class BudgetObserver
{
    /**
     * Handle the Budget "created" event.
     */
    public function created(Budget $budget): void
    {
        $pet=$budget->pet;

        Log::create([
            "action"=>'CREACIÓN DE PRESUPUESTO',
            "description"=>'Se creo un nuevo presupuesto para la mascota ' .$pet->name,
            "user_id"=>(Auth::user()->id)
        ]);
    }

    /**
     * Handle the Budget "updated" event.
     */
    public function updated(Budget $budget): void
    {
        $pet=$budget->pet;

        Log::create([
            "action"=>'EDICIÓN DE PRESUPUESTO',
            "description"=>'Se edito el presupuesto para la mascota ' .$pet->name,
            "user_id"=>(Auth::user()->id)
        ]);
    }

    /**
     * Handle the Budget "deleted" event.
     */
    public function deleted(Budget $budget): void
    {
        $pet=$budget->pet;

        Log::create([
            "action"=>'ELIMINACIÓN DE PRESUPUESTO',
            "description"=>'Se elimino el presupuesto para la mascota ' .$pet->name,
            "user_id"=>(Auth::user()->id)
        ]);
    }

    /**
     * Handle the Budget "restored" event.
     */
    public function restored(Budget $budget): void
    {
        //
    }

    /**
     * Handle the Budget "force deleted" event.
     */
    public function forceDeleted(Budget $budget): void
    {
        //
    }
}
