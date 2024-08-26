<?php

namespace App\Http\Controllers;

use App\Models\AttentionStatus;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\AttentionStatusRequest;
use Dotenv\Repository\RepositoryInterface;

/**
 * Class AttentionStatusController
 * @package App\Http\Controllers
 */
class AttentionStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", AttentionStatus::class);
        return view('attention-status.index');
    }

    public function list()
    {
        $areas = AttentionStatus::all();
        return DataTables::of($areas)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("create", AttentionStatus::class);
        $attentionStatus = new AttentionStatus();
        
        return view('attention-status.create', compact('attentionStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttentionStatusRequest $request)
    {
        $this->authorize("create", AttentionStatus::class);
        AttentionStatus::create($request->validated());

        return redirect()->route('attention-statuses.index')
            ->with('success', 'Registro creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $attentionStatus = AttentionStatus::find($id);
        $this->authorize("view", $attentionStatus);
        return view('attention-status.show', compact('attentionStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attentionStatus = AttentionStatus::find($id);
        $this->authorize("update", $attentionStatus);
        return view('attention-status.edit', compact('attentionStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttentionStatusRequest $request, AttentionStatus $attentionStatus)
    {       
        $this->authorize("update", $attentionStatus);
        $attentionStatus->update($request->validated());

        return redirect()->route('attention-statuses.index')
            ->with('success', 'Registro actualizado correctamente');
    }

    public function destroy($id)
    {
        $attentionStatus = AttentionStatus::find($id);
        $this->authorize("delete", $attentionStatus);
        $attentionStatus->delete();

        return response()->json($attentionStatus);
    }
}
