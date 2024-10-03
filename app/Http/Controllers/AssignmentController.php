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
        $this->authorize("viewAny", Assignment::class);
        return view('assignment.index');
    }

    public function list(){
        $receptions = Reception::with(['receptionType','family','pet','reason',
            'statusHistory'])->get();
    
        return DataTables::of($receptions)
             ->addColumn('status', function ($reception) {
                 return  $reception->statusHistory->first()->attentionStatus->name ;
             })
             ->make(true);
     }
    
    
}
