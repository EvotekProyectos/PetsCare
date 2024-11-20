<?php

namespace App\Http\Controllers;

use App\Models\FormatType;
use App\Http\Requests\FormatTypeRequest;

/**
 * Class FormatTypeController
 * @package App\Http\Controllers
 */
class FormatTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formatTypes = FormatType::paginate();

        return view('format-type.index', compact('formatTypes'))
            ->with('i', (request()->input('page', 1) - 1) * $formatTypes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $formatType = new FormatType();
        return view('format-type.create', compact('formatType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormatTypeRequest $request)
    {
        FormatType::create($request->validated());

        return redirect()->route('format-types.index')
            ->with('success', 'FormatType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $formatType = FormatType::find($id);

        return view('format-type.show', compact('formatType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $formatType = FormatType::find($id);

        return view('format-type.edit', compact('formatType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormatTypeRequest $request, FormatType $formatType)
    {
        $formatType->update($request->validated());

        return redirect()->route('format-types.index')
            ->with('success', 'FormatType updated successfully');
    }

    public function destroy($id)
    {
        FormatType::find($id)->delete();

        return redirect()->route('format-types.index')
            ->with('success', 'FormatType deleted successfully');
    }
}
