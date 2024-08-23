<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Http\Requests\AreaRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class AreaController
 * @package App\Http\Controllers
 */
class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", Area::class);
        return view('area.index');
    }

    public function list()
    {
        $areas = Area::all();
        return DataTables::of($areas)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $area = new Area();
        $this->authorize("create", $area);
        return view('area.create', compact('area'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AreaRequest $request)
    {
        $this->authorize("create", Area::class);
        Area::create($request->validated());

        return redirect()->route('areas.index')
            ->with('success', 'Area created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $area = Area::find($id);
        $this->authorize("view", $area);
        return view('area.show', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $area = Area::find($id);
        $this->authorize("update", $area);
        return view('area.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AreaRequest $request, Area $area)
    {
        $this->authorize("update", $area);
        $area->update($request->validated());

        return redirect()->route('areas.index')
            ->with('success', 'Area updated successfully');
    }

    public function destroy($id)
    {
        $area = Area::find($id);
        $this->authorize("delete", $area);
        $area->delete();

        return response()->json($area);
    }
}
