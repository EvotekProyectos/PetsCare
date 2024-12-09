<?php

namespace App\Http\Controllers;

use App\Models\Hospitalization;
use App\Http\Requests\HospitalizationRequest;
use App\Models\FollowUp;
use App\Models\Producto;
use App\Models\Reception;

/**
 * Class HospitalizationController
 * @package App\Http\Controllers
 */
class HospitalizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospitalizations = Hospitalization::paginate();
        $this->authorize("viewAny", Hospitalization::class);
        return view('hospitalization.index', compact('hospitalizations'))
            ->with('i', (request()->input('page', 1) - 1) * $hospitalizations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hospitalization = new Hospitalization();
        $this->authorize("create", Hospitalization::class);
        return view('hospitalization.create', compact('hospitalization'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HospitalizationRequest $request)
    {
        Hospitalization::create($request->validated());
        $this->authorize("create", Hospitalization::class);
        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalization created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hospitalization = Hospitalization::find($id);
        $this->authorize("viewAny", $hospitalization);
        return view('hospitalization.show', compact('hospitalization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $hospitalization = Hospitalization::find($id);
        $this->authorize("update", $hospitalization);

        return view('hospitalization.edit', compact('hospitalization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HospitalizationRequest $request, Hospitalization $hospitalization)
    {
        $hospitalization->update($request->validated());
        $this->authorize("update", $hospitalization);

        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalization updated successfully');
    }

    public function destroy($id)
    {
        Hospitalization::find($id)->delete();

        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalization deleted successfully');
    }

    public function historic(int $id){
        $reception = Reception::find($id);

        return view('hospitalization.historic', compact('reception'));
    }

    public function followups(int $id){
        $reception = Reception::find($id);
        $followupsCritic = new FollowUp();

        return view('follow-up.add', compact('reception', 'followupsCritic'));
    }

    public function test(){
        dd(Producto::all()); 
    }
}
