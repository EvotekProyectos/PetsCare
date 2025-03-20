<?php

namespace App\Http\Controllers;

use App\Models\ControlDate;
use App\Http\Requests\ControlDateRequest;
use App\Models\DateType;
use App\Models\Family;
use App\Models\Pet;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ControlDateController
 * @package App\Http\Controllers
 */
class ControlDateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $controlDates = ControlDate::paginate();

        return view('control-date.index', compact('controlDates'))
            ->with('i', (request()->input('page', 1) - 1) * $controlDates->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $controlDate = new ControlDate();
        $families = Family::all();
        $pets = Pet::all(); 
        $types = DateType::all();
        return view('control-date.create', compact('controlDate' ,'families', 'pets', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ControlDateRequest $request)
    {
        $data = $request->validated();
        $data['status_date_id'] = 1;

        ControlDate::create($data);

        return redirect()->route('control-dates.index')
            ->with('success', 'ControlDate created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $controlDate = ControlDate::find($id);

        return view('control-date.show', compact('controlDate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $controlDate = ControlDate::find($id);

        return view('control-date.edit', compact('controlDate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ControlDateRequest $request, ControlDate $controlDate)
    {
        $controlDate->update($request->validated());

        return redirect()->route('control-dates.index')
            ->with('success', 'ControlDate updated successfully');
    }

    public function destroy($id)
    {
        ControlDate::find($id)->delete();

        return redirect()->route('control-dates.index')
            ->with('success', 'ControlDate deleted successfully');
    }

    public function list(){
        $dates = ControlDate::with('family', 'pet', 'user', 'dateType','reception','statusDate' )->get();
        return DataTables::of($dates)->make(true);
    }
}
