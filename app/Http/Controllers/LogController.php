<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Requests\LogRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class LogController
 * @package App\Http\Controllers
 */
class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", Log::class);
        return view('log.index');
    }

    public function list(){
        $logs = Log::with('user')->orderBy('id', 'desc')->get();
        return DataTables::of($logs)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
        // $log = new Log();
        // return view('log.create', compact('log'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LogRequest $request)
    {
        Log::create($request->validated());

        return redirect()->route('logs.index')
            ->with('success', 'Log created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        abort(404);
        // $log = Log::find($id);
        // return view('log.show', compact('log'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort(404);
        // $log = Log::find($id);
        // return view('log.edit', compact('log'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LogRequest $request, Log $log)
    {
        // $log->update($request->validated());

        // return redirect()->route('logs.index')
        //     ->with('success', 'Log updated successfully');
    }

    public function destroy($id)
    {
        // Log::find($id)->delete();

        // return redirect()->route('logs.index')
        //     ->with('success', 'Log deleted successfully');
    }
}
