<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Assignment;
use App\Models\AttentionStatus;
use App\Models\GroomingStatus;
use App\Models\Hospitalization;
use App\Models\HospitalizationStatus;
use App\Models\HospitalizationStatusHistory;
use App\Models\Reception;
use App\Models\ReceptionStatusHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssignmentController extends Controller
{

    public function index()
    {
        $this->authorize("viewAny", Reception::class);

        $attentionStatuses = AttentionStatus::all();
        $enEsperaStatusId = AttentionStatus::where('name', 'En espera')->value('id');
        $enConsultaStatusId = AttentionStatus::where('name', 'En consulta')->value('id');

        return view('assignment.index', compact('attentionStatuses', 'enEsperaStatusId', 'enConsultaStatusId'));
    }


    public function appointments(Request $request)
    {
        $user = auth()->user();
        $this->authorize("viewAny", Appointment::class);

        $receptions = Reception::with(['receptionType', 'family', 'pet', 'pet.species', 'reason', 'room', 'statusHistory', 'vet', 'currentStatusAppointment.attentionStatus'])
            ->where('veterinarian_id', $user->id)
            ->where('reception_type_id', 1)
            // "Fecha" en esta tabla es entry_date 
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('entry_date', $request->date);
            })
            ->when($request->filled('status_id'), function ($query) use ($request) {
                $query->whereHas('currentStatusAppointment', function ($q) use ($request) {
                    $q->where('attention_status_id', $request->status_id);
                });
            })
            ->get();

        return DataTables::of($receptions)
            ->addColumn('status', function ($reception) {
                return $reception->currentStatusAppointment?->attentionStatus?->name;
            })
            ->addColumn('status_id', function ($reception) {
                return $reception->currentStatusAppointment?->attentionStatus?->id;
            })
            ->make(true);
    }

    /**
     *usado por el polling del front para saber si hay que
     * recargar
     */
    public function lastUpdateAppointments()
    {
        $lastChange = collect([
            Reception::where('reception_type_id', 1)->max('updated_at'),
            ReceptionStatusHistory::max('updated_at'),
        ])->filter()->max();

        return response()->json(['last_update' => $lastChange]);
    }

    public function hospital()
    {
        // $this->authorize("viewAny", Reception::class);

        $hospitalizationStatuses = HospitalizationStatus::all();

        return view('hospitalization.table', compact('hospitalizationStatuses'));
    }

    public function hospitalizations(Request $request)
    {
        $user = auth()->user();
        $receptions = Reception::with(['admissionType', 'family', 'pet','pet.species', 'vet', 'area', 
        'currentHospitalizationStatus.hospitalizationStatus'])
            ->where('reception_type_id', 2)
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('entry_date', $request->date);
            })
            ->when($request->filled('status_id'), function ($query) use ($request) {
                $query->whereHas('currentHospitalizationStatus', function ($q) use ($request) {
                    $q->where('hospitalization_status_id', $request->status_id);
                });
            })
            ->get();


           return DataTables::of($receptions)
            ->addColumn('status', function ($reception) {
                return $reception->currentHospitalizationStatus?->hospitalizationStatus?->name;
            })
            ->addColumn('status_id', function ($reception) {
                return $reception->currentHospitalizationStatus?->hospitalizationStatus?->id;
            })
            ->make(true);
    }

    /**
     * ver lastUpdateAppointments
     */
    public function lastUpdateHospitalizations()
    {
        $lastChange = collect([
            Reception::where('reception_type_id', 2)->max('updated_at'),
            HospitalizationStatusHistory::max('updated_at'),
        ])->filter()->max();

        return response()->json(['last_update' => $lastChange]);
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

    public function groomings(Request $request)
    {
        if (request()->ajax()) {
            $datas = Reception::with('pet', 'vet', 'statusGrooming.groomingStatus', 'grooming')
                ->where('reception_type_id', 3)
                ->when($request->filled('date'), function ($query) use ($request) {
                    $query->whereDate('entry_date', $request->date);
                })
                ->when($request->filled('status_id'), function ($query) use ($request) {
                    $query->whereHas('currentStatusGrooming', function ($q) use ($request) {
                        $q->where('grooming_status_id', $request->status_id);
                    });
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

        $groomingStatuses = GroomingStatus::all();

        return view('assignment.grooming', compact('groomingStatuses'));
    }

    public function delivery()
    {
        $this->authorize('viewAny', 'delivery');
        $now = Carbon::now();
        if (request()->ajax()) {
            $datas = Reception::with('pet', 'vet', 'statusGrooming.groomingStatus', 'grooming')
                ->whereDate('created_at', $now)
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
