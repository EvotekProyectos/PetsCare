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


    public function appointments()
    {
        $user = auth()->user();
        $receptions = Reception::with(['receptionType', 'family', 'pet', 'reason', 'room', 'statusHistory'])
            ->where('veterinarian_id', $user->id)
            ->where('reception_type_id', 1)
            ->get();

        return DataTables::of($receptions)
            ->addColumn('status', function ($reception) {
                return  $reception->statusHistory->last()->attentionStatus->name;
            })
            ->addColumn('status_id', function ($reception) {
                return  $reception->statusHistory->last()->attentionStatus->id;
            })
            ->make(true);
    }

    public function hospital()
    {
        $this->authorize("viewAny", Reception::class);
        return view('hospitalization.table');
    }

    public function hospitalizations()
    {
        $user = auth()->user();
        $receptions = Reception::with(['admissionType', 'family', 'pet', 'vet', 'area'])
            ->where('reception_type_id', 2)
            ->get();

        return DataTables::of($receptions)
            ->make(true);
    }
}
