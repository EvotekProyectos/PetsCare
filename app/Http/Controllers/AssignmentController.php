<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Assignment;
use App\Models\Hospitalization;
use App\Models\Reception;
use Carbon\Carbon;
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
        $now = Carbon::now();
        $this->authorize("viewAny", Appointment::class);
        $receptions = Reception::with(['receptionType', 'family', 'pet', 'reason', 'room', 'statusHistory'])
            ->where('veterinarian_id', $user->id)
            ->whereDate('created_at', $now )
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
            ->whereNull('exit_date')
            ->get();

        return DataTables::of($receptions)
            ->make(true);
    }

    public function hospital_altas()
    {
        $this->authorize("viewAny", Reception::class);
        return view('hospitalization.table_recep');
    }


    public function altas()
    {
        $hospitalizations = Hospitalization::with(['reception.admissionType', 'reception.family', 'reception.pet', 'reception.vet', 'reception.area', 'hospitalDischarges'])
            ->whereHas('reception', function ($query) {
                $query->where('reception_type_id', 2);
            })
            ->get();

        return DataTables::of($hospitalizations)->make(true);
    }

    public function groomings()
    {
        $now = Carbon::now();
        if (request()->ajax()) {
            $datas = Reception::with('pet','vet','statusGrooming.groomingStatus', 'grooming')
            ->whereDate('created_at', $now )
            ->where('reception_type_id', 3) 
            ->get();

            return DataTables::of($datas)
            ->addColumn('status', function ($data) {
                $status = $data->statusGrooming->last();
                if ($status && $status->groomingStatus) {
                    return [
                        'name' => $status->groomingStatus->name,
                        'color' => $status->groomingStatus->color,
                    ];
                }
                return [
                    'name' => 'Sin Estado',
                    'color' => '#cccccc', 
                ];
            })
            ->make(true);
        }

        return view('assignment.grooming');
    }

    public function delivery() {
        $now = Carbon::now();
        if (request()->ajax()) {
            $datas = Reception::with('pet','vet','statusGrooming.groomingStatus', 'grooming')
            ->whereDate('created_at', $now )
            ->where('reception_type_id', 3) 
            ->whereHas('grooming', function ($query) {
                $query->where('delivery_service', 1);
            })
            ->get();

            return DataTables::of($datas)
            ->addColumn('status', function ($data) {
                $status = $data->statusGrooming->last();
                if ($status && $status->groomingStatus) {
                    return [
                        'name' => $status->groomingStatus->name,
                        'color' => $status->groomingStatus->color,
                    ];
                }
                return [
                    'name' => 'Sin Estado',
                    'color' => '#cccccc', 
                ];
            })
            ->make(true);
        } 

        return view('assignment.delivery');
        
    }
}
