<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Http\Requests\PrescriptionRequest;
use App\Models\Appointment;
use App\Models\ControlDate;
use App\Models\DateType;
use App\Models\Log;
use App\Models\Pet;
use App\Models\Reason;
use App\Models\Reception;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class PrescriptionController
 * @package App\Http\Controllers
 */
class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $prescriptions = Prescription::paginate();
        $this->authorize("viewAny", Prescription::class);

        return view('prescription.index', compact('prescriptions'))
            ->with('i', (request()->input('page', 1) - 1) * $prescriptions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */

    //   public function create()
    //   {
    //       $prescription = new Prescription();
    //       $this->authorize("create",Prescription::class);
    //       return view('prescription.create', compact('prescription'));
    //   }

    public function create($id)
    {
        $pet = Pet::find($id);
        $prescription = new Prescription();
        $reasons = Reason::all();
        $this->authorize("create", Prescription::class);
        return view('prescription.create', compact('prescription', 'pet', 'reasons'));
    }


    /**
     * Store a newly created resource in storage.
     */


    public function store(PrescriptionRequest $request)
    {
        $this->authorize("create", Prescription::class);

        $new = Prescription::create($request->validated());

        return response()->json($new);
    }


    public function storeControlDate(PrescriptionRequest $request)
    {
        $this->authorize("create", Prescription::class);

        $new = Prescription::create($request->validated());

        $reception = $new->reception;
        $pet = Pet::find($request->pet_id);

        $day_next_check = $request->day_next_check;
        $time_next_check = $request->time_next_check;

        // Si no se define time_next_check, se asigna las 8:00 am
        if (empty($time_next_check)) {
            $time_next_check = '08:00:00';
        }

        // Concatenar la fecha y la hora para lograr el formato de tipo datetime
        $datetime = $day_next_check . ' ' . $time_next_check;


        ControlDate::createIfNotDuplicate([
            'reception' => $reception ? $reception->id : null,
            'pet_id' => $request->pet_id,
            'family_id' => $pet ? $pet->family_id : null,
            'date_type_id' => $request->reason_next_check_id,
            'status_date_id' => 1,
            'user_id' => auth()->id(),
            'date' => $datetime,
        ]);

        return response()->json($new);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $prescription = Prescription::find($id);
        $this->authorize("view", Prescription::class);
        return view('prescription.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $prescription = Prescription::find($id);
        $pet = $prescription->pet;
        $reasons = Reason::all();
        $this->authorize("update", $prescription);
        return view('prescription.edit', compact('prescription', 'pet', 'reasons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrescriptionRequest $request, Prescription $prescription)
    {
        $prescription->update($request->validated());
        $this->authorize("update", $prescription);
        return redirect()->route('prescriptions.index')
            ->with('success', 'Prescription updated successfully');
    }

    public function destroy($id)
    {
        $prescription = Prescription::find($id);
        $this->authorize("delete", $prescription);
        $prescription->delete();

        return response()->json($prescription);
    }

    public function list()
    {
        $prescription = Prescription::with('reception', 'vet', 'receptionist')->get();
        return DataTables::of($prescription)->make(true);
    }

    public function imprimir(int $id)
    {
        $prescription = Prescription::with(
            "vet",
            "reception"
        )->find($id);
        $reception = $prescription->reception_id;

        $next = Appointment::where("reception_id", $reception)->get()->First();
        $pdf = Pdf::loadView("prescription.pdf", compact("prescription", "next"));
        return $pdf->stream("PDF.pdf");
    }

    public function new(int $id)
    {
        $reception = Reception::findOrFail($id);
        $pet = Pet::findOrFail($reception->pet_id);
        $prescription = new Prescription();
        $reasons = Reason::all();
        $this->authorize("create", Prescription::class);
        return view('prescription.new', compact('prescription', 'pet', 'reasons', 'reception'));
    }
}
