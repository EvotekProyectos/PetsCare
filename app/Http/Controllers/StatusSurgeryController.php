<?php

namespace App\Http\Controllers;

use App\Models\StatusSurgery;
use App\Http\Requests\StatusSurgeryRequest;

/**
 * Class StatusSurgeryController
 * @package App\Http\Controllers
 */
class StatusSurgeryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statusSurgeries = StatusSurgery::paginate();

        return view('status-surgery.index', compact('statusSurgeries'))
            ->with('i', (request()->input('page', 1) - 1) * $statusSurgeries->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statusSurgery = new StatusSurgery();
        return view('status-surgery.create', compact('statusSurgery'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StatusSurgeryRequest $request)
    {
        StatusSurgery::create($request->validated());

        return redirect()->route('status-surgeries.index')
            ->with('success', 'StatusSurgery created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $statusSurgery = StatusSurgery::find($id);

        return view('status-surgery.show', compact('statusSurgery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $statusSurgery = StatusSurgery::find($id);

        return view('status-surgery.edit', compact('statusSurgery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StatusSurgeryRequest $request, StatusSurgery $statusSurgery)
    {
        $statusSurgery->update($request->validated());

        return redirect()->route('status-surgeries.index')
            ->with('success', 'StatusSurgery updated successfully');
    }

    public function destroy($id)
    {
        StatusSurgery::find($id)->delete();

        return redirect()->route('status-surgeries.index')
            ->with('success', 'StatusSurgery deleted successfully');
    }
}
