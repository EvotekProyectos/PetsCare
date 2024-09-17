<?php

namespace App\Http\Controllers;

use App\Models\CoverArea;
use App\Http\Requests\CoverAreaRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CoverAreaController
 * @package App\Http\Controllers
 */
class CoverAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coverAreas = CoverArea::paginate();
        $this->authorize("viewAny", CoverArea::class);

        return view('cover-area.index', compact('coverAreas'))
            ->with('i', (request()->input('page', 1) - 1) * $coverAreas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coverArea = new CoverArea();
        $this->authorize("create", CoverArea::class);
        return view('cover-area.create', compact('coverArea'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CoverAreaRequest $request)
    {
        $this->authorize("create", CoverArea::class);
        CoverArea::create($request->validated());

        return redirect()->route('cover-areas.index')
            ->with('success', 'CoverArea created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coverArea = CoverArea::find($id);
        $this->authorize("view", CoverArea::class);

        return view('cover-area.show', compact('coverArea'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coverArea = CoverArea::find($id);
        $this->authorize("update", $coverArea);

        return view('cover-area.edit', compact('coverArea'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CoverAreaRequest $request, CoverArea $coverArea)
    {
        $this->authorize("update", $coverArea);
        $coverArea->update($request->validated());

        return redirect()->route('cover-areas.index')
            ->with('success', 'CoverArea updated successfully');
    }

    public function destroy($id)
    {
        $coverArea = CoverArea::find($id);
        $this->authorize("delete", $coverArea);
        $coverArea->delete();

        return response()->json($coverArea);
    }

    public function list()
    {
        $coverArea = CoverArea::all();

        return DataTables::of($coverArea) ->make(true);
    }
}
