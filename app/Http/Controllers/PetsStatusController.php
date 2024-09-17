<?php

namespace App\Http\Controllers;

use App\Models\PetsStatus;
use App\Http\Requests\PetsStatusRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class PetsStatusController
 * @package App\Http\Controllers
 */
class PetsStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petsStatuses = PetsStatus::paginate();
        $this->authorize("viewAny", PetsStatus::class);

        return view('pets-status.index', compact('petsStatuses'))
            ->with('i', (request()->input('page', 1) - 1) * $petsStatuses->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $petsStatus = new PetsStatus();
        $this->authorize("create", PetsStatus::class);
        return view('pets-status.create', compact('petsStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PetsStatusRequest $request)
    {
        PetsStatus::create($request->validated());
        $this->authorize("create", PetsStatus::class);
        return redirect()->route('pets-statuses.index')
            ->with('success', 'PetsStatus created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $petsStatus = PetsStatus::find($id);
        $this->authorize("view", PetsStatus::class);

        return view('pets-status.show', compact('petsStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $petsStatus = PetsStatus::find($id);
        $this->authorize("update", $petsStatus);

        return view('pets-status.edit', compact('petsStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetsStatusRequest $request, PetsStatus $petsStatus)
    {
        $petsStatus->update($request->validated());
        $this->authorize("update", $petsStatus);

        return redirect()->route('pets-statuses.index')
            ->with('success', 'PetsStatus updated successfully');
    }

    public function destroy($id)
    {
        $petsStatus = PetsStatus::find($id);
        $this->authorize("delete", $petsStatus);
        $petsStatus->delete();

        return response()->json($petsStatus);
    }

    public function list()
    {
        $petsStatus = PetsStatus::all();

        return DataTables::of($petsStatus) ->make(true);
    }
}
