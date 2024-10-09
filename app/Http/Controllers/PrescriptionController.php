<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Http\Requests\PrescriptionRequest;
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
    public function create()
    {
        $prescription = new Prescription();
        $this->authorize("create",Prescription::class);
        return view('prescription.create', compact('prescription'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PrescriptionRequest $request)
    {
        $new = Prescription::create($request->validated());

        return response()->json($new);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $prescription = Prescription::find($id);

        return view('prescription.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $prescription = Prescription::find($id);

        return view('prescription.edit', compact('prescription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrescriptionRequest $request, Prescription $prescription)
    {
        $prescription->update($request->validated());

        return redirect()->route('prescriptions.index')
            ->with('success', 'Prescription updated successfully');
    }

    public function destroy($id)
    {
        Prescription::find($id)->delete();

        return redirect()->route('prescriptions.index')
            ->with('success', 'Prescription deleted successfully');
    }

    public function list(){
        $prescription= Prescription::with('reception','vet', 'receptionist')->get();
        return DataTables::of($prescription)->make(true);
    }
}
