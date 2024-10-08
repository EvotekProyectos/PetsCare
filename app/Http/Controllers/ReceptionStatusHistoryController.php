<?php

namespace App\Http\Controllers;

use App\Models\ReceptionStatusHistory;
use App\Http\Requests\ReceptionStatusHistoryRequest;

/**
 * Class ReceptionStatusHistoryController
 * @package App\Http\Controllers
 */
class ReceptionStatusHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receptionStatusHistories = ReceptionStatusHistory::paginate();

        return view('reception-status-history.index', compact('receptionStatusHistories'))
            ->with('i', (request()->input('page', 1) - 1) * $receptionStatusHistories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $receptionStatusHistory = new ReceptionStatusHistory();
        return view('reception-status-history.create', compact('receptionStatusHistory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReceptionStatusHistoryRequest $request)
    {
        ReceptionStatusHistory::create($request->validated());

        return redirect()->route('reception-status-histories.index')
            ->with('success', 'ReceptionStatusHistory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $receptionStatusHistory = ReceptionStatusHistory::find($id);

        return view('reception-status-history.show', compact('receptionStatusHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $receptionStatusHistory = ReceptionStatusHistory::find($id);

        return view('reception-status-history.edit', compact('receptionStatusHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReceptionStatusHistoryRequest $request, ReceptionStatusHistory $receptionStatusHistory)
    {
        $receptionStatusHistory->update($request->validated());

        return redirect()->route('reception-status-histories.index')
            ->with('success', 'ReceptionStatusHistory updated successfully');
    }

    public function destroy($id)
    {
        ReceptionStatusHistory::find($id)->delete();

        return redirect()->route('reception-status-histories.index')
            ->with('success', 'ReceptionStatusHistory deleted successfully');
    }
}
