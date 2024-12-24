<?php

namespace App\Http\Controllers;

use App\Models\GroomingStatus;
use App\Http\Requests\GroomingStatusRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class GroomingStatusController
 * @package App\Http\Controllers
 */
class GroomingStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groomingStatuses = GroomingStatus::paginate();
        $this->authorize("viewAny", GroomingStatus::class);
        return view('grooming-status.index', compact('groomingStatuses'))
            ->with('i', (request()->input('page', 1) - 1) * $groomingStatuses->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("create", GroomingStatus::class);
        $groomingStatus = new GroomingStatus();
        return view('grooming-status.create', compact('groomingStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroomingStatusRequest $request)
    {
        $this->authorize("create", GroomingStatus::class);
        GroomingStatus::create($request->validated());

        return redirect()->route('grooming-statuses.index')
            ->with('success', 'Registro exitoso');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $groomingStatus = GroomingStatus::find($id);
        $this->authorize("view", $groomingStatus);

        return view('grooming-status.show', compact('groomingStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $groomingStatus = GroomingStatus::find($id);
        $this->authorize("update", $groomingStatus);

        return view('grooming-status.edit', compact('groomingStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroomingStatusRequest $request, GroomingStatus $groomingStatus)
    {
        $this->authorize("update", $groomingStatus);
        $groomingStatus->update($request->validated());

        return redirect()->route('grooming-statuses.index')
            ->with('success', 'Edición exitosa');
    }

    public function destroy($id)
    {
        $groomingStatus = GroomingStatus::find($id);
        $this->authorize("delete", $groomingStatus);
        $groomingStatus->delete();

        return response()->json($groomingStatus);
    }

    public function list()
    {
        $groomingStatus = GroomingStatus::all();

        return DataTables::of($groomingStatus)->make(true);
    }
}
