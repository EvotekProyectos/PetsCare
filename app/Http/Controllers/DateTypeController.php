<?php

namespace App\Http\Controllers;

use App\Models\DateType;
use App\Http\Requests\DateTypeRequest;

/**
 * Class DateTypeController
 * @package App\Http\Controllers
 */
class DateTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dateTypes = DateType::paginate();

        return view('date-type.index', compact('dateTypes'))
            ->with('i', (request()->input('page', 1) - 1) * $dateTypes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dateType = new DateType();
        return view('date-type.create', compact('dateType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DateTypeRequest $request)
    {
        DateType::create($request->validated());

        return redirect()->route('date-types.index')
            ->with('success', 'DateType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dateType = DateType::find($id);

        return view('date-type.show', compact('dateType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dateType = DateType::find($id);

        return view('date-type.edit', compact('dateType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DateTypeRequest $request, DateType $dateType)
    {
        $dateType->update($request->validated());

        return redirect()->route('date-types.index')
            ->with('success', 'DateType updated successfully');
    }

    public function destroy($id)
    {
        DateType::find($id)->delete();

        return redirect()->route('date-types.index')
            ->with('success', 'DateType deleted successfully');
    }
}
