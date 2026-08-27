<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetHistory;
use App\Models\Reception;
use App\Models\User;
use Illuminate\Http\Request;

class PetHistoryController extends Controller
{
    public function index($id, Request $request)
    {
        //$this->authorize("viewAny", PetHistory::class);
        $pet = Pet::with(['genre', 'reproductiveStatus', 'petClassification', 'file'])->find($id);

        $typeFilter = $request->query('type');

        $vetIds = Reception::where('pet_id', $id)
            ->whereNotNull('veterinarian_id')
            ->distinct()
            ->pluck('veterinarian_id');
        $vets = User::whereIn('id', $vetIds)->orderBy('name')->get();

        $hasTransfers = Reception::where('pet_id', $id)->whereHas('transfersFrom')->exists();

        return view('pet_history.view', compact('pet', 'typeFilter', 'vets', 'hasTransfers'));
    }

}



