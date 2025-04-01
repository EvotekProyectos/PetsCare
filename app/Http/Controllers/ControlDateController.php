<?php

namespace App\Http\Controllers;

use App\Models\ControlDate;
use App\Http\Requests\ControlDateRequest;
use App\Models\DateType;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Schedule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

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
        $controlDates = ControlDate::paginate();

        return view('control-date.index', compact('controlDates'))
            ->with('i', (request()->input('page', 1) - 1) * $controlDates->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $controlDate = new ControlDate();
        $families = Family::all();
        $pets = Pet::all(); 
        $types = DateType::all();
        $schedules =Schedule::all();
        return view('control-date.create', compact('controlDate' ,'families', 'pets', 'types', 'schedules'));
    }
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(ControlDateRequest $request)
    {
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
        $families = Family::all();
        $pets = Pet::all(); 
        $types = DateType::all();
        $schedules =Schedule::all();

        return view('control-date.edit', compact('controlDate', 'families', 'pets', 'types', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ControlDateRequest $request, ControlDate $controlDate)
    {
        $controlDate->update($request->validated());

        return redirect()->route('control-dates.index')
            ->with('success', 'ControlDate updated successfully');
    }

    public function destroy($id)
    {
        ControlDate::find($id)->delete();

        return redirect()->route('control-dates.index')
            ->with('success', 'Cita actualizada correctamente');
    }

    public function list(){
        $dates = ControlDate::with('family', 'pet', 'user', 'dateType','reception','statusDate' )->get();
        return DataTables::of($dates)->make(true);
    }

    public function updateDate(Request $request, $id)
{
    // Validar que se reciba una fecha válida
    $request->validate([
        'date' => 'required|date'
    ]);

    // Buscar la cita por ID y actualizar la fecha
    $controlDate = ControlDate::find($id);

    if (!$controlDate) {
        return response()->json(['success' => false, 'message' => 'Cita no encontrada'], 404);
    }

    $controlDate->date = $request->date;
    $controlDate->save();

    return response()->json(['success' => true, 'message' => 'Fecha actualizada correctamente']);
}

public function calendar()
{
    $dates = ControlDate::with(['reception'])
        ->get();


    return view('control-date.calendar', compact('dates'));
}

public function getEvents()
{

    // Obtener los eventos con las relaciones necesarias
    $allEvents = ControlDate::with('family', 'pet', 'dateType', 'reception')
    ->whereHas('statusDate', function ($query) {
        $query->where('id', 3); // Filtramos por statusDate = 1
    })
    ->get();
    $events = [];

    foreach ($allEvents as $event) {

        $events[] = [

            'title' => ($event->dateType->name) . ' para ' . ($event->pet->name ?? 'Sin mascota'),
            //'pet' => ($event->reception->pet->name ?? 'Sin mascota') . ' Collar:' . ($event->reception->num ?? 'Sin collar'),
            //  'start' => $event->cubicle->start_date,
            //  'end' => $event->cubicle->end_date,
            'start' => $event->date ?? '',
            //'status'=> $event->statusDate->name ?? '',
            //'end' => $event->extension == 0 ? ($event->reception->exit_date ?? '') : ($event->cubicle->end_date ?? ''),

            'backgroundColor' =>'#8aef6d',
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
        'status_date_id' => 'required|integer|in:2,3',  // Aceptamos solo los valores 2 y 3
    ]);

    // Buscar la cita por su ID
    $controlDate = ControlDate::find($id);

    // Verificar si la cita existe
    if (!$controlDate) {
        return response()->json(['success' => false, 'message' => 'Cita no encontrada.'], 404);
    }

    // Actualizar el status_date_id
    $controlDate->status_date_id = $request->status_date_id;
    $controlDate->save();  // Guardar los cambios en la base de datos

    // Retornar una respuesta exitosa
    return response()->json(['success' => true, 'message' => 'Estado de la cita actualizado correctamente.']);
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

}