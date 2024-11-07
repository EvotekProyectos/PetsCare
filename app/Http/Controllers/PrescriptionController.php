<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Http\Requests\PrescriptionRequest;
use App\Models\Appointment;
use App\Models\Pet;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $this->authorize("viewAny",Prescription::class);

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
      {   $pet = Pet::find($id);
          $prescription = new Prescription();
          $this->authorize("create",Prescription::class);
          return view('prescription.create', compact('prescription','pet'));
     }


    /**
     * Store a newly created resource in storage.
     */
     public function store(PrescriptionRequest $request)
     {
         $new = Prescription::create($request->validated());
         $this->authorize("create",Prescription::class);
         
         return response()->json($new);
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $prescription = Prescription::find($id);
        $this->authorize("view",Prescription::class);
        return view('prescription.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $prescription = Prescription::find($id);
        $pet = $prescription->pet;
        $this->authorize("update",$prescription);
        return view('prescription.edit', compact('prescription', 'pet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrescriptionRequest $request, Prescription $prescription)
    {
        $prescription->update($request->validated());
        $this->authorize("update",$prescription);
        return redirect()->route('prescriptions.index')
            ->with('success', 'Prescription updated successfully');
    }

    public function destroy($id)
    {
        $prescription= Prescription::find($id);
        $this->authorize("delete", $prescription);
        $prescription->delete();

        return response()->json($prescription);
    }

    public function list(){
        $prescription= Prescription::with('reception','vet', 'receptionist')->get();
        return DataTables::of($prescription)->make(true);
    }

    public function imprimir(int $id)
    {
        $prescription = Prescription::with(
            "vet",
            "reception"
        )->find($id);
        $reception= $prescription->reception_id;
        
        $next=Appointment::where("reception_id", $reception)->get()->First();       
        $pdf = Pdf::loadView("prescription.pdf", compact("prescription", "next"));
        return $pdf->stream("PDF.pdf");
    }

   
}
