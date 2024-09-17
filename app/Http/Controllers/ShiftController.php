<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Http\Requests\ShiftRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ShiftController
 * @package App\Http\Controllers
 */
class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::paginate();
        $this->authorize("viewAny", Shift::class);

        return view('shift.index', compact('shifts'))
            ->with('i', (request()->input('page', 1) - 1) * $shifts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shift = new Shift();
        $this->authorize("create", Shift::class);
        return view('shift.create', compact('shift'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftRequest $request)
    {
        $this->authorize("create", Shift::class);
        Shift::create($request->validated());

        return redirect()->route('shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorize("view", Shift::class);
        $shift = Shift::find($id);

        return view('shift.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $shift = Shift::find($id);
        $this->authorize("update", $shift);
        return view('shift.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftRequest $request, Shift $shift)
    {
        $shift->update($request->validated());
        $this->authorize("update", $shift);

        return redirect()->route('shifts.index')
            ->with('success', 'Shift updated successfully');
    }

    public function destroy($id)
    {
        $shift = Shift::find($id);
        $this->authorize("delete", $shift);
        $shift->delete();

        return response()->json($shift);
    }

    public function list()
    {
        $shift = Shift::all();

        return DataTables::of($shift) ->make(true);
    }
}
