<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\AppointmentRequest;
use App\Models\AppointmentService;
use App\Models\Prescription;
use App\Models\Producto;
use App\Models\ProductType;
use App\Models\Reason;
use App\Models\Reception;
use App\Models\ReceptionStatusHistory;
use App\Models\VaccineCertificate;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class AppointmentController
 * @package App\Http\Controllers
 */
class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::paginate();
        $this->authorize("viewAny", Appointment::class);

        return view('appointment.index', compact('appointments'))
            ->with('i', (request()->input('page', 1) - 1) * $appointments->perPage());
    }


    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $appointment = new Appointment();
        $reasons = Reason::all();
        $prescription = new Prescription();
       
        $this->authorize("create", Appointment::class);
        return view('appointment.create', compact('appointment', 'reasons', 'prescription', ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $new  = Appointment::create($request->validated());
        ReceptionStatusHistory::create([
            'reception_id' => $request->reception_id,
            'attention_status_id' => 1,
        ]);
        $this->authorize("create", Appointment::class);

        return response()->json($new);

        // return redirect()->route('assignment.index')
        //     ->with('success', 'Consulta Finalizada Exitosamente, puedes seguir atendiendo al siguiente paciente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);
        $this->authorize("view", Appointment::class);

        return view('appointment.show', compact('appointment'));
    }
  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointment = Appointment::find($id);
        $reasons = Reason::all();
        $this->authorize("update", $appointment);

        return view('appointment.edit', compact('appointment', 'reasons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());
        $this->authorize("update", $appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully');
    }

    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        $this->authorize("update", $appointment);
        $appointment->delete();

        return response()->json($appointment);
    }


    public function list($id)
{
    $appointments = Appointment::with('reception', 'reason')->where('reception_id', $id)->get();
    return view('appointment.index', compact('appointments'));
}


    public function consultation(int $id)
    {
        $appointment = new Appointment();
        $reception = Reception::with('pet', 'reason')->findorfail($id);
        $reasons = Reason::all();
        $prescription = new Prescription();
        $vaccineCertificate = new VaccineCertificate();
        $appointmentService = new AppointmentService();
        $products = Producto::where("ESTATUS",  "A")->get();
        $this->authorize("create", Appointment::class);
        // ReceptionStatusHistory::create([
        //     'reception_id' => $id,
        //     'attention_status_id' => 3,
        // ]);
        return view('appointment.create', compact('appointment', 'reasons', 'prescription', 'reception', 'vaccineCertificate', 'products','appointmentService'));
    }

    public function historic(int $id)
    {
        $reception = Reception::find($id);
        $appointment = Appointment::where("reception_id", $id)->get()->first();
        $prescription = Prescription::where("reception_id", $id)->get()->first();

        return view('appointment.historic', compact('appointment', 'prescription', 'reception'));
    }
}
