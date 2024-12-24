<?php

namespace App\Http\Controllers;

use App\Models\GroomingStatusHistory;
use App\Http\Requests\GroomingStatusHistoryRequest;

/**
 * Class GroomingStatusHistoryController
 * @package App\Http\Controllers
 */
class GroomingStatusHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groomingStatusHistories = GroomingStatusHistory::paginate();

        return view('grooming-status-history.index', compact('groomingStatusHistories'))
            ->with('i', (request()->input('page', 1) - 1) * $groomingStatusHistories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groomingStatusHistory = new GroomingStatusHistory();
        return view('grooming-status-history.create', compact('groomingStatusHistory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroomingStatusHistoryRequest $request)
    {
        GroomingStatusHistory::create($request->validated());

        return redirect()->route('grooming-status-histories.index')
            ->with('success', 'GroomingStatusHistory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $groomingStatusHistory = GroomingStatusHistory::find($id);

        return view('grooming-status-history.show', compact('groomingStatusHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $groomingStatusHistory = GroomingStatusHistory::find($id);

        return view('grooming-status-history.edit', compact('groomingStatusHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroomingStatusHistoryRequest $request, GroomingStatusHistory $groomingStatusHistory)
    {
        $groomingStatusHistory->update($request->validated());

        return redirect()->route('grooming-status-histories.index')
            ->with('success', 'GroomingStatusHistory updated successfully');
    }

    public function destroy($id)
    {
        GroomingStatusHistory::find($id)->delete();

        return redirect()->route('grooming-status-histories.index')
            ->with('success', 'GroomingStatusHistory deleted successfully');
    }
}
