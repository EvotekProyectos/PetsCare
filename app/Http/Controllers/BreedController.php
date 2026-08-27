<?php

namespace App\Http\Controllers;

use App\Models\Breed;

/**
 * Class BreedController
 * @package App\Http\Controllers
 */
class BreedController extends Controller
{
    /**
     * Razas activas de una especie
     */
    public function data($species)
    {
        $breeds = Breed::where('species_id', $species)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return response()->json($breeds);
    }
}
