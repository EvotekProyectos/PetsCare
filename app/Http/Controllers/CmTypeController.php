<?php

namespace App\Http\Controllers;

use App\Models\CmType;
use App\Http\Requests\CmTypeRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CmTypeController
 * @package App\Http\Controllers
 */
class CmTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cmTypes = CmType::paginate();
        $this->authorize("viewAny", CmType::class);
        return view('cm-type.index', compact('cmTypes'))
            ->with('i', (request()->input('page', 1) - 1) * $cmTypes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cmType = new CmType();
        $this->authorize("create", CmType::class);
        return view('cm-type.create', compact('cmType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CmTypeRequest $request)
    {
        CmType::create($request->validated());
        $this->authorize("create", CmType::class);
        return redirect()->route('cm-types.index')
            ->with('success', 'CmType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cmType = CmType::find($id);
        $this->authorize("view", CmType::class);
        return view('cm-type.show', compact('cmType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cmType = CmType::find($id);
        $this->authorize("update", $cmType);
        return view('cm-type.edit', compact('cmType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CmTypeRequest $request, CmType $cmType)
    {
        $cmType->update($request->validated());
        $this->authorize("update", $cmType);
        return redirect()->route('cm-types.index')
            ->with('success', 'C.M actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cm=CmType::find($id);
        $this->authorize("delete", $cm);
        $cm->delete();
        return response()->json($cm);
    }

    public function list()
    {
        $cms = CmType::all();

        return DataTables::of($cms) ->make(true);
    }

}
