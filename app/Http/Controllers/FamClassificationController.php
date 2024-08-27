<?php

namespace App\Http\Controllers;

use App\Models\FamClassification;
use App\Http\Requests\FamClassificationRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class FamClassificationController
 * @package App\Http\Controllers
 */
class FamClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $famClassifications = FamClassification::paginate();
        $this->authorize("viewAny", FamClassification::class);

        return view('fam-classification.index', compact('famClassifications'))
            ->with('i', (request()->input('page', 1) - 1) * $famClassifications->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $famClassification = new FamClassification();
        $this->authorize("create", FamClassification::class);
        return view('fam-classification.create', compact('famClassification'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FamClassificationRequest $request)
    {
        FamClassification::create($request->validated());
        $this->authorize("create", FamClassification::class);

        return redirect()->route('fam-classifications.index')
            ->with('success', 'FamClassification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $famClassification = FamClassification::find($id);
        $this->authorize("view", FamClassification::class);

        return view('fam-classification.show', compact('famClassification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $famClassification = FamClassification::find($id);
        $this->authorize("update", $famClassification);

        return view('fam-classification.edit', compact('famClassification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FamClassificationRequest $request, FamClassification $famClassification)
    {
        $famClassification->update($request->validated());
        $this->authorize("update", $famClassification);

        return redirect()->route('fam-classifications.index')
            ->with('success', 'FamClassification updated successfully');
    }

    public function destroy($id)
    {
        $famClassification = FamClassification::find($id);
        $this->authorize("delete", $famClassification);
        $famClassification->delete();

        return response()->json($famClassification);
    }

    public function list()
    {
        $famClassification = FamClassification::all();

        return DataTables::of($famClassification) ->make(true);
    }
}
