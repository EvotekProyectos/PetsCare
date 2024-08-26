<?php

namespace App\Http\Controllers;

use App\Models\ReproductiveStatus;
use App\Http\Requests\ReproductiveStatusRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ReproductiveStatusController
 * @package App\Http\Controllers
 */
class ReproductiveStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reproductiveStatuses = ReproductiveStatus::paginate();
        $this->authorize("viewAny", ReproductiveStatus::class);

        return view('reproductive-status.index', compact('reproductiveStatuses'))
            ->with('i', (request()->input('page', 1) - 1) * $reproductiveStatuses->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reproductiveStatus = new ReproductiveStatus();
        $this->authorize("create", ReproductiveStatus::class);
        return view('reproductive-status.create', compact('reproductiveStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReproductiveStatusRequest $request)
    {
        ReproductiveStatus::create($request->validated());
        $this->authorize("create", ReproductiveStatus::class);
        return redirect()->route('reproductive-statuses.index')
            ->with('success', 'ReproductiveStatus created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reproductiveStatus = ReproductiveStatus::find($id);
        $this->authorize("view", ReproductiveStatus::class);
        return view('reproductive-status.show', compact('reproductiveStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reproductiveStatus = ReproductiveStatus::find($id);
        $this->authorize("update", $reproductiveStatus);
        return view('reproductive-status.edit', compact('reproductiveStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReproductiveStatusRequest $request, ReproductiveStatus $reproductiveStatus)
    {
        $reproductiveStatus->update($request->validated());
        $this->authorize("update", $reproductiveStatus);
        return redirect()->route('reproductive-statuses.index')
            ->with('success', 'ReproductiveStatus updated successfully');
    }

    public function destroy($id)
    {
        $reproductiveStatus = ReproductiveStatus::find($id);
        $this->authorize("delete", $reproductiveStatus);
        $reproductiveStatus->delete();

        return response()->json($reproductiveStatus);
    }

    public function list(){
        $reproductiveStatus = ReproductiveStatus::all();

        return DataTables::of($reproductiveStatus) ->make(true);
    }
}
