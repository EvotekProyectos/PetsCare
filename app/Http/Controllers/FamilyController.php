<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Http\Requests\FamilyRequest;
use App\Models\FamClassification;
use App\Models\Room;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class FamilyController
 * @package App\Http\Controllers
 */
class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $families = Family::paginate();
        $this->authorize("viewAny", Family::class);

        return view('family.index', compact('families'))
            ->with('i', (request()->input('page', 1) - 1) * $families->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $family = new Family();
        $FamClassifications = FamClassification::all();
        $this->authorize("create", Family::class);

        return view('family.create', compact('family', 'FamClassifications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FamilyRequest $request)
    {
        Family::create($request->validated());
        $this->authorize("create", Family::class);

        return redirect()->route('families.index')
            ->with('success', 'Family created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $family = Family::find($id);
        $this->authorize("view", Family::class);

        return view('family.show', compact('family'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $family = Family::find($id);
        $FamClassifications = FamClassification::all();
        $this->authorize("update", $family);

        return view('family.edit', compact('family', 'FamClassifications'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FamilyRequest $request, Family $family)
    {
        $family->update($request->validated());
        $this->authorize("update", $family);

        return redirect()->route('families.index')
            ->with('success', 'Family updated successfully');
    }

    public function destroy($id)
    {
        $family =Family::find($id);
        $this->authorize("delete", $family);
        $family->delete();

        return response()->json($family);
    }

    public function list()
    {
        $family = Family::with("famClassification")->get();

        return DataTables::of($family) ->make(true);
    }
}
