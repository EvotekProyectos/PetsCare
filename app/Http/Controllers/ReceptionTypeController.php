<?php

namespace App\Http\Controllers;

use App\Models\ReceptionType;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ReceptionTypeRequest;

/**
 * Class ReceptionTypeController
 * @package App\Http\Controllers
 */
class ReceptionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", ReceptionType::class);
        return view('reception-type.index');
    }

    public function list()
    {
        $reasons = ReceptionType::all();
        return DataTables::of($reasons)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("create", ReceptionType::class);
        $receptionType = new ReceptionType();
        return view('reception-type.create', compact('receptionType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReceptionTypeRequest $request)
    {
        $this->authorize("create", ReceptionType::class);
        ReceptionType::create($request->validated());

        return redirect()->route('reception-types.index')
            ->with('success', 'Registro creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $receptionType = ReceptionType::find($id);
        $this->authorize("view", $receptionType);

        return view('reception-type.show', compact('receptionType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $receptionType = ReceptionType::find($id);
        $this->authorize("update", $receptionType);
        return view('reception-type.edit', compact('receptionType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReceptionTypeRequest $request, ReceptionType $receptionType)
    {
        $this->authorize("update", $receptionType);
        $receptionType->update($request->validated());

        return redirect()->route('reception-types.index')
            ->with('success', 'Registro actualizado correctamente');
    }

    public function destroy($id)
    {
        $receptionType = ReceptionType::find($id);
        $this->authorize("delete", $receptionType);
        $receptionType->delete();

        return response()->json($receptionType);
    }
}
