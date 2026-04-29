<?php

namespace App\Http\Controllers;

use App\Exports\ControlDatesExport;
use App\Models\ControlDate;
use App\Http\Requests\ControlDateRequest;
use App\Models\DateType;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Schedule;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Exports\UsuariosExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ControlDateController
 * @package App\Http\Controllers
 */
class ControlDateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", ControlDate::class);
        $controlDates = ControlDate::paginate();

        return view('control-date.index', compact('controlDates'))
            ->with('i', (request()->input('page', 1) - 1) * $controlDates->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("create", ControlDate::class);
        $controlDate = new ControlDate();
        $families = Family::all();
        $pets = Pet::all();
        $types = DateType::all();
        $schedules = Schedule::all();
        return view('control-date.create', compact('controlDate', 'families', 'pets', 'types', 'schedules'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ControlDateRequest $request)
    {
        $this->authorize("create", ControlDate::class);
        $data = $request->validated();
        $data['status_date_id'] = 1;
        $data['user_id'] = auth()->id();
        ControlDate::create($data);

        return redirect()->route('control-dates.index')
            ->with('success', 'Cita creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $controlDate = ControlDate::find($id);

        return view('control-date.show', compact('controlDate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $controlDate = ControlDate::find($id);
        $this->authorize("update", $controlDate);
        $families = Family::all();
        $pets = Pet::all();
        $types = DateType::all();
        $schedules = Schedule::all();

        return view('control-date.edit', compact('controlDate', 'families', 'pets', 'types', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ControlDateRequest $request, ControlDate $controlDate)
    {
        $this->authorize("update", $controlDate);
        $controlDate->update($request->validated());

        return redirect()->route('control-dates.index')
            ->with('success', 'Cita actualizada correctamente');
    }

    public function destroy($id)
    {
        $controlDate = ControlDate::find($id);
        $this->authorize("delete", $controlDate);
        $controlDate->delete();

        return response()->json($controlDate);
    }

    public function list()
    {
        $dates = ControlDate::with('family', 'pet', 'user', 'dateType', 'reception', 'statusDate')->get();
        return DataTables::of($dates)->make(true);
    }


    public function calendar()
    {
        $dates = ControlDate::with(['reception'])
            ->get();


        return view('control-date.calendar', compact('dates'));
    }

    public function calendarIndividual($id)
    {
        $dates = ControlDate::with(['reception', 'schedule'])
            ->whereHas('schedule', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->get();

        return view('control-date.calendarIndividual', compact('dates'));
    }

    public function listConfirmedIndividual($id)
    {
        $allEvents = ControlDate::with('family', 'pet', 'dateType', 'reception', 'schedule', 'schedule.user')
            ->whereHas('statusDate', function ($query) {
                $query->where('id', 3);
            })
            ->whereHas('schedule', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->get();

        return response()->json($allEvents);
    }

    public function listConfirmed()
    {
        $allEvents = ControlDate::with('family', 'pet', 'dateType', 'reception', 'schedule', 'schedule.user')
            ->whereHas('statusDate', function ($query) {
                $query->where('id', 3);
            })
            ->get();

        return response()->json($allEvents);
    }

    public function getEvents()
    {

        // Obtener los eventos con las relaciones necesarias
        $allEvents = ControlDate::with('family', 'pet', 'dateType', 'reception', 'schedule', 'schedule.user')
            ->whereHas('statusDate', function ($query) {
                $query->where('id', 3);
            })
            ->get();
        $events = [];

        foreach ($allEvents as $event) {

            $events[] = [

                'title' => ($event->dateType->name) . ' para ' . ($event->pet->name ?? 'Sin mascota'),
                'mvz' => $event->schedule->user->name ?? '',
                //'pet' => ($event->reception->pet->name ?? 'Sin mascota') . ' Collar:' . ($event->reception->num ?? 'Sin collar'),
                //  'start' => $event->cubicle->start_date,
                //  'end' => $event->cubicle->end_date,
                'start' => $event->date ?? '',
                //'status'=> $event->statusDate->name ?? '',
                //'end' => $event->extension == 0 ? ($event->reception->exit_date ?? '') : ($event->cubicle->end_date ?? ''),

                'backgroundColor' => '#8aef6d',
                'borderColor' => '#8aef6d',
                'textColor' => '#000000', // Letra en negro

            ];
        }
        // Retornar la respuesta como JSON
        return response()->json($events);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validar la entrada
        $request->validate([
            'status_date_id' => 'required|integer|in:2,3',
            'schedule_id' => 'required'  // Aceptamos solo los valores 2 y 3
        ]);

        // Buscar la cita por su ID
        $controlDate = ControlDate::find($id);

        // Actualizar el status_date_id
        $controlDate->status_date_id = $request->status_date_id;
        $controlDate->schedule_id = $request->schedule_id ?? null;
        $controlDate->save();  // Guardar los cambios en la base de datos

        // Retornar una respuesta exitosa
        return response()->json(['success' => true, 'message' => 'Estado de la cita actualizado correctamente.']);
    }

    public function updateDate(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $controlDate = ControlDate::find($id);
        $controlDate->date = $request->date;
        $controlDate->status_date_id = 2;  // Se cambia a 2 automáticamente
        $controlDate->save();

        return response()->json(['success' => true, 'message' => 'Fecha actualizada correctamente.']);
    }


    public function schedules($id)
    {
        // Buscar la cita en control_dates
        $controlDate = ControlDate::find($id);

        // Verificar si existe la cita
        if (!$controlDate) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }

        // Obtener la fecha de la cita
        $date = $controlDate->date;

        // Buscar los schedules que contengan esa fecha en su rango
        $schedules = Schedule::with('user', 'coverArea')->where('begin', '<=', $date)
            ->where('end', '>=', $date)
            ->get();

        return response()->json($schedules);
    }

    public function updateAttend(Request $request, $id)
    {
        // Validar la entrada
        $request->validate([
            'status' => 'required'
        ]);

        // Buscar la cita y actualizar el estado
        $controlDate = ControlDate::find($id);
        if (!$controlDate) {
            return response()->json(['success' => false, 'message' => 'Cita no encontrada.'], 404);
        }

        $controlDate->status = $request->status;
        $controlDate->save();

        return response()->json(['success' => true, 'message' => 'Estado de la cita actualizado correctamente.']);
    }

    public function validateSchedule(Request $request)
    {
        $selectedDate = Carbon::parse($request->date);
        $scheduleId = $request->schedule_id;

        $startRange = (clone $selectedDate)->subMinutes(30);
        $endRange = (clone $selectedDate)->addMinutes(30);

        $conflict = ControlDate::where('status_date_id', 3)
            ->where('schedule_id', $scheduleId) // Validación por médico
            ->whereBetween('date', [$startRange, $endRange])
            ->exists();

        return response()->json(['conflict' => $conflict]);
    }


    public function showDate($id)
    {
        $cita = ControlDate::findOrFail($id);
        return response()->json($cita);
    }

    public function exportarExcel()
    {
        return Excel::download(new ControlDatesExport, 'citas_proximas.xlsx');
    }
}
