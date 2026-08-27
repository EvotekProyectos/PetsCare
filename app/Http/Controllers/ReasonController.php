<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use App\Models\Producto;
use App\Http\Requests\ReasonRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ReasonController
 * @package App\Http\Controllers
 */
class ReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", Reason::class);
        return view('reason.index');
    }
    
    public function list(){
        $reasons = Reason::all();
        return DataTables::of($reasons) ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reason = new Reason();
        $products = Producto::where("ESTATUS", "A")->get();
        $this->authorize("create", Reason::class);
        return view('reason.create', compact('reason', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReasonRequest $request)
    {
        $this->authorize("create", Reason::class);
        Reason::create($request->validated());
        return redirect()->route('reasons.index')
            ->with('success', 'Reason created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorize("view", Reason::class);
        $reason = Reason::find($id);
        return view('reason.show', compact('reason'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $reason = Reason::find($id);
        $products = Producto::where("ESTATUS", "A")->get();
        $this->authorize("update", Reason::class);
        return view('reason.edit', compact('reason', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReasonRequest $request, Reason $reason)
    {
        $this->authorize("update", Reason::class);
        $reason->update($request->validated());

        return redirect()->route('reasons.index')
            ->with('success', 'Reason updated successfully');
    }

    public function destroy($id)
    {
        $reason = Reason::find($id);
        $this->authorize("delete", Reason::class);
        $reason->delete();

        return response()->json($reason);
    }
}
