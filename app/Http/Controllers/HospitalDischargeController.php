<?php

namespace App\Http\Controllers;

use App\Models\HospitalDischarge;
use App\Http\Requests\HospitalDischargeRequest;

/**
 * Class HospitalDischargeController
 * @package App\Http\Controllers
 */
class HospitalDischargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospitalDischarges = HospitalDischarge::paginate();

        return view('hospital-discharge.index', compact('hospitalDischarges'))
            ->with('i', (request()->input('page', 1) - 1) * $hospitalDischarges->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hospitalDischarge = new HospitalDischarge();
        return view('hospital-discharge.create', compact('hospitalDischarge'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HospitalDischargeRequest $request)
    {
        HospitalDischarge::create($request->validated());

        return redirect()->route('hospital-discharges.index')
            ->with('success', 'HospitalDischarge created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hospitalDischarge = HospitalDischarge::find($id);

        return view('hospital-discharge.show', compact('hospitalDischarge'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $hospitalDischarge = HospitalDischarge::find($id);

        return view('hospital-discharge.edit', compact('hospitalDischarge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HospitalDischargeRequest $request, HospitalDischarge $hospitalDischarge)
    {
        $hospitalDischarge->update($request->validated());

        return redirect()->route('hospital-discharges.index')
            ->with('success', 'HospitalDischarge updated successfully');
    }

    public function destroy($id)
    {
        HospitalDischarge::find($id)->delete();

        return redirect()->route('hospital-discharges.index')
            ->with('success', 'HospitalDischarge deleted successfully');
    }
}
