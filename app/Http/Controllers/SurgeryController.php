<?php

namespace App\Http\Controllers;

use App\Models\Surgery;
use App\Http\Requests\SurgeryRequest;
use App\Models\ProductType;
use App\Models\Reception;
use App\Models\User;

/**
 * Class SurgeryController
 * @package App\Http\Controllers
 */
class SurgeryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surgeries = Surgery::paginate();
        $this->authorize("viewAny", Surgery::class);
        return view('surgery.index', compact('surgeries'))
            ->with('i', (request()->input('page', 1) - 1) * $surgeries->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $surgery = new Surgery();
        $products= ProductType::all();
        $reception = Reception::find($id);
        $this->authorize("create", Surgery::class);

        return view('surgery.create', compact('surgery',  'products', 'reception'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurgeryRequest $request)
    {
        $this->authorize("create", Surgery::class);
        $surgery = new Surgery($request->validated());
        $surgery->vet_id = auth()->id();
        $surgery->save();
    
        return redirect()->route('surgeries.index')
            ->with('success', 'Surgery created successfully.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surgery = Surgery::find($id);

        return view('surgery.show', compact('surgery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $surgery = Surgery::find($id);
    $reception = $surgery->reception;
    $vets= $surgery->user;
    $products = ProductType::all(); 

    $this->authorize("update", $surgery);

    return view('surgery.edit', compact('surgery', 'reception', 'products', 'vets'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(SurgeryRequest $request, Surgery $surgery)
    {
        $surgery->update($request->validated());

        return redirect()->route('surgeries.index')
            ->with('success', 'Surgery updated successfully');
    }

    public function destroy($id)
    {
        Surgery::find($id)->delete();

        return redirect()->route('surgeries.index')
            ->with('success', 'Surgery deleted successfully');
    }
}
