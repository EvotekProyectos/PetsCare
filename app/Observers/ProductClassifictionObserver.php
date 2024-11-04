<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\ProductClassification;
use Illuminate\Support\Facades\Auth;

class ProductClassifictionObserver
{
    /**
     * Handle the ProductClassification "created" event.
     */
    public function created(ProductClassification $productClassification): void
    {
        Log::create([
            "action" => "CREACION DE NUEVA CLASIFICACIÓN DE PRODUCTO",
            'description' => 'Se creó una nueva clasificación de producto: ' . $productClassification->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ProductClassification "updated" event.
     */
    public function updated(ProductClassification $productClassification): void
    {
        Log::create([
            "action" => "EDICIÓN DE CLASIFICACIÓN DE PRODUCTO",
            'description' => 'Se editó una clasificación de producto: ' . $productClassification->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ProductClassification "deleted" event.
     */
    public function deleted(ProductClassification $productClassification): void
    {
        Log::create([
            "action" => "ELIMINACIÓN DE CLASIFICACIÓN DE PRODUCTO",
            'description' => 'Se eliminó una clasificación de producto: ' . $productClassification->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ProductClassification "restored" event.
     */
    public function restored(ProductClassification $productClassification): void
    {
        //
    }

    /**
     * Handle the ProductClassification "force deleted" event.
     */
    public function forceDeleted(ProductClassification $productClassification): void
    {
        //
    }
}
