<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Reception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssignmentController extends Controller
{
    
    public function index()
    {
        $this->authorize("viewAny", Reception::class);
        return view('assignment.index');

    }


    public function list(){
        $user = auth()->user();
        $receptions = Reception::with(['receptionType','family','pet','reason', 'statusHistory'])
        ->where('veterinarian_id', $user->id)
        ->get();
    
        return DataTables::of($receptions)
             ->addColumn('status', function ($reception) {
                 return  $reception->statusHistory->last()->attentionStatus->name ;
             })
             ->addColumn('status_id', function ($reception) {
                return  $reception->statusHistory->last()->attentionStatus->id ;
            })
             ->make(true);
     }
    
    
}
