<?php

namespace App\Http\Controllers;

use App\Models\PetClassification;
use App\Http\Requests\PetClassificationRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class PetClassificationController
 * @package App\Http\Controllers
 */
class PetClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petClassifications = PetClassification::paginate();
        $this->authorize("viewAny", PetClassification::class);

        return view('pet-classification.index', compact('petClassifications'))
            ->with('i', (request()->input('page', 1) - 1) * $petClassifications->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $petClassification = new PetClassification();
        $this->authorize("create", PetClassification::class);
        return view('pet-classification.create', compact('petClassification'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PetClassificationRequest $request)
    {
        $this->authorize("create", PetClassification::class);
        PetClassification::create($request->validated());
        return redirect()->route('pet-classifications.index')
            ->with('success', 'PetClassification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $petClassification = PetClassification::find($id);
        $this->authorize("view", PetClassification::class);

        return view('pet-classification.show', compact('petClassification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $petClassification = PetClassification::find($id);
        $this->authorize("update", $petClassification);

        return view('pet-classification.edit', compact('petClassification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetClassificationRequest $request, PetClassification $petClassification)
    {
        $petClassification->update($request->validated());
        $this->authorize("update", $petClassification);
        return redirect()->route('pet-classifications.index')
            ->with('success', 'PetClassification updated successfully');
    }

    public function destroy($id)
    {
        $petClassification = PetClassification::find($id);
        $this->authorize("delete", $petClassification);
        $petClassification->delete();

        return response()->json($petClassification);
    }

    public function list()
    {
        $petClassification = PetClassification::all();

        return DataTables::of($petClassification) ->make(true);
    }
}
