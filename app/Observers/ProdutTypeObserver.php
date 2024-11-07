<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\ProductType;
use Illuminate\Support\Facades\Auth;

class ProdutTypeObserver
{
    /**
     * Handle the ProductType "created" event.
     */
    public function created(ProductType $productType): void
    {
        Log::create([
            "action" => "CREACION DE NUEVO TIPO DE PRODUCTO",
            'description' => 'Se creó un nuevo tipo de producto: ' . $productType->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ProductType "updated" event.
     */
    public function updated(ProductType $productType): void
    {
        Log::create([
            "action" => "EDICIÓN DE  TIPO DE PRODUCTO",
            'description' => 'Se editó un tipo de producto: ' .$productType->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ProductType "deleted" event.
     */
    public function deleted(ProductType $productType): void
    {
        Log::create([
            "action" => "ELIMINACIÓN DE TIPO DE PRODUCTO",
            'description' => 'Se eliminó un tipo de producto: ' . $productType->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ProductType "restored" event.
     */
    public function restored(ProductType $productType): void
    {
        //
    }

    /**
     * Handle the ProductType "force deleted" event.
     */
    public function forceDeleted(ProductType $productType): void
    {
        //
    }
}
