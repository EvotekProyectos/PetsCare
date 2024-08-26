<?php

namespace App\Http\Controllers;

use App\Models\AdmissionType;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\AdmissionTypeRequest;

/**
 * Class AdmissionTypeController
 * @package App\Http\Controllers
 */
class AdmissionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", AdmissionType::class);
        return view('admission-type.index');
    }

    public function list()
    {
        $areas = AdmissionType::all();
        return DataTables::of($areas)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("create", AdmissionType::class);
        $admissionType = new AdmissionType();
        return view('admission-type.create', compact('admissionType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdmissionTypeRequest $request)
    {
        $this->authorize("create", AdmissionType::class);
        AdmissionType::create($request->validated());

        return redirect()->route('admission-types.index')
            ->with('success', 'Registro creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $admissionType = AdmissionType::find($id);
        $this->authorize("view", $admissionType);
        return view('admission-type.show', compact('admissionType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $admissionType = AdmissionType::find($id);
        $this->authorize("update", $admissionType);
        return view('admission-type.edit', compact('admissionType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdmissionTypeRequest $request, AdmissionType $admissionType)
    {
        $this->authorize("update", $admissionType);
        $admissionType->update($request->validated());

        return redirect()->route('admission-types.index')
            ->with('success', 'Registro actualizado correctamente');
    }

    public function destroy($id)
    {
        $admissionType = AdmissionType::find($id);
        $this->authorize("delete", $admissionType);
        $admissionType->delete();

        return response()->json($admissionType);
    }
}
