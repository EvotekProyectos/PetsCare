<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('dashboard.view');

    }


    public function appointments()
{
    $appointments = Reception::where('reception_type_id', 1)
        ->selectRaw('reason_id, COUNT(*) as total')
        ->groupBy('reason_id')
        ->get();

    return response()->json($appointments);
}


    // public function appointments(){
    //     $appointments= Reception::where('reception_type_id',1)->with('vet','pet','reason')->get();
        
    //     return response()->json($appointments);
    // }

    // public function hospital(){
    //     $hospital= Reception::where('reception_type_id',2)->with('vet','pet','reason')->get();
    //     return response()->json($hospital);
    // }
}

