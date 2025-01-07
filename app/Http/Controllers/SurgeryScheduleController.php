<?php

namespace App\Http\Controllers;

use App\Models\SurgerySchedule;
use App\Http\Requests\SurgeryScheduleRequest;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Producto;
use App\Models\Reception;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class SurgeryScheduleController
 * @package App\Http\Controllers
 */
class SurgeryScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surgerySchedules = SurgerySchedule::paginate();
        $this->authorize("viewAny", SurgerySchedule::class);
        return view('surgery-schedule.index', compact('surgerySchedules'))
            ->with('i', (request()->input('page', 1) - 1) * $surgerySchedules->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $this->authorize("create", SurgerySchedule::class);
        //$reception= Reception::find($id);
        $surgerySchedule = new SurgerySchedule();
        //$surgerySchedule->reception_id=$id;
        $users = User::all();
        $families = Family::all();
        $pets = Pet::all(); 
        $products = Producto::where("ESTATUS",  "A")->get();
        

        return view('surgery-schedule.create', compact('surgerySchedule', 'users', 'families', 'pets', 'products'));
    }

    public function add($id){

        $this->authorize("create", SurgerySchedule::class);
        $reception = Reception::with('family', 'pet')->find($id);

         $surgerySchedule = new SurgerySchedule();
         $surgerySchedule->reception_id=$id;
         //$surgerySchedule->save();
         
         $users = User::all();
         //$families = Family::all();
         //$pets = Pet::all(); 
         $products = Producto::where("ESTATUS",  "A")->get();
         
 
         return view('surgery-schedule.create2', compact('surgerySchedule', 'users', 'products', 'reception'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurgeryScheduleRequest $request)
    {
        $this->authorize("create", SurgerySchedule::class);
        $surgerySchedule= SurgerySchedule::create($request->validated());
        $surgerySchedule->status_surgery_id=1;
        $surgerySchedule->save();
        return redirect()->route('surgery-schedules.index')
            ->with('success', 'Asignación creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surgerySchedule = SurgerySchedule::find($id);

        return view('surgery-schedule.show', compact('surgerySchedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        

        $surgerySchedule = SurgerySchedule::find($id);
        $users = User::all();
        $families = Family::all();
        $pets = Pet::all(); 
        $products = Producto::where("ESTATUS",  "A")->get();

        $this->authorize("update", $surgerySchedule);

        return view('surgery-schedule.edit', compact('surgerySchedule', 'users', 'families', 'pets', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SurgeryScheduleRequest $request, SurgerySchedule $surgerySchedule)
    {
        $surgerySchedule->update($request->validated());

        return redirect()->route('surgery-schedules.index')
            ->with('success', 'Horario actualizado correctamente');
    }

    public function destroy($id)
    {
        $ss=SurgerySchedule::find($id);

        $ss->delete();

        return response()->json($ss);
    }

    public function list()
    {
        $surgerySchedule = SurgerySchedule::with('family', 'pet', 'user', 'statusSurgery','producto' )->get();
        return DataTables::of($surgerySchedule)->make(true);
    }

    public function getEvents()
    {
        $allEvents = SurgerySchedule::with('family', 'pet', 'user', 'statusSurgery')->get();
        $events = [];
    
        foreach ($allEvents as $event) {

            $formattedHour = date('H:i', strtotime($event->hour));

            $events[] = [
                'title' => ($event->producto->NOMBRE ?? 'Sin producto') . ' para: ' . ($event->pet->name ?? 'Sin mascota'),
                'medice' => ($event->user->name ?? 'Sin veterinario'),
                 'start' => $event->day . 'T' . $formattedHour, 
                // 'end' => date('Y-m-d\TH:i:s', strtotime($event->day . ' ' . $event->hour . ' +1 hour')),
                'description' => $event->statusSurgery->name ?? 'Sin estado',
                'backgroundColor' => $event->statusSurgery->color,
                'borderColor' => $event->statusSurgery->color,
            ];
        }
    
        return response()->json($events);
    }

    public function assignament(){
        return view('surgery-schedule/assignment-surgeries');

    }
    
    public function updateStatus($id, Request $request)
{
    $surgerySchedule = SurgerySchedule::findOrFail($id);

    if ($request->status == 1) {
        $surgerySchedule->status_surgery_id = 2;
    } elseif ($request->status == 2) {
        $surgerySchedule->status_surgery_id = 3;
    }
    $surgerySchedule->save();

    return response()->json(['success' => true, 'status' => $surgerySchedule->status_surgery_id]);
}

    
    
}
