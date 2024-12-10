<?php

namespace App\Http\Controllers;

use App\Models\SurgeryPack;
use App\Http\Requests\SurgeryPackRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class SurgeryPackController
 * @package App\Http\Controllers
 */
class SurgeryPackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surgeryPacks = SurgeryPack::paginate();
        $this->authorize("viewAny", SurgeryPack::class);
        return view('surgery-pack.index', compact('surgeryPacks'))
            ->with('i', (request()->input('page', 1) - 1) * $surgeryPacks->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $surgeryPack = new SurgeryPack();
        $this->authorize("create", SurgeryPack::class);
        return view('surgery-pack.create', compact('surgeryPack'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurgeryPackRequest $request)
    {
        $this->authorize("create", SurgeryPack::class);
        SurgeryPack::create($request->validated());
        return redirect()->route('surgery-packs.index')
            ->with('success', 'Paquete de cirugia creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surgeryPack = SurgeryPack::find($id);
        $this->authorize("view", $surgeryPack);
        return view('surgery-pack.show', compact('surgeryPack'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $surgeryPack = SurgeryPack::find($id);
        $this->authorize("update", $surgeryPack);
        return view('surgery-pack.edit', compact('surgeryPack'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SurgeryPackRequest $request, SurgeryPack $surgeryPack)
    {
        $this->authorize("update", $surgeryPack);
        $surgeryPack->update($request->validated());
        return redirect()->route('surgery-packs.index')
            ->with('success', 'Paquete de cirugia editado exitosamente');
    }

    public function destroy($id)
    {
        $surgeryPack = SurgeryPack::find($id);
        $this->authorize("delete", $surgeryPack);
        $surgeryPack->delete();

        return response()->json($surgeryPack);
    }

    public function list()
    {
        $packs= SurgeryPack::all();

        return DataTables::of($packs)->make(true);
    }
}
