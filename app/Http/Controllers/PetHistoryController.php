<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\PetHistory;
use Illuminate\Http\Request;

class PetHistoryController extends Controller
{
    public function index($id)
    {
        //$this->authorize("viewAny", PetHistory::class);
        $pet = Pet::find($id);
        $petHistory = $pet->family;
        $petHistory = $pet->genre;
        $petHistory = $pet->petClassification;
        $petHistory = $pet->file;
        $petHistory = $pet->reproductiveStatus;

        return view('pet_history.view' , compact('pet', 'petHistory'));

    }


}
