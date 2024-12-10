<?php

namespace App\Http\Controllers;

use App\Models\FollowupsCritic;
use App\Http\Requests\FollowupsCriticRequest;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class FollowupsCriticController
 * @package App\Http\Controllers
 */
class FollowupsCriticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $followupsCritics = FollowupsCritic::paginate();
        $this->authorize("viewAny", FollowupsCritic::class);

        return view('followups-critic.index', compact('followupsCritics'))
            ->with('i', (request()->input('page', 1) - 1) * $followupsCritics->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $followupsCritic = new FollowupsCritic();
        $this->authorize("create", FollowupsCritic::class);
        return view('followups-critic.create', compact('followupsCritic'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FollowupsCriticRequest $request)
    {
        $new = FollowupsCritic::create($request->validated());
        $this->authorize("create", FollowupsCritic::class);

        return response()->json($new); 
        // return redirect()->route('followups-critics.index')
        //     ->with('success', 'FollowupsCritic created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $followupsCritic = FollowupsCritic::find($id);
        $this->authorize("view", $followupsCritic);

        return view('followups-critic.show', compact('followupsCritic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $followupsCritic = FollowupsCritic::find($id);
        $this->authorize("update", $followupsCritic);
        return view('followups-critic.edit', compact('followupsCritic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FollowupsCriticRequest $request, FollowupsCritic $followupsCritic)
    {
        $followupsCritic->update($request->validated());
        $this->authorize("update", $followupsCritic);
        $reception = $request->reception_id;
        
        return redirect()->route('hospitalization.followups', $reception)
            ->with('success', 'Seguimiento Editado');
    }

    public function destroy($id)
    {
        $followupsCritic = FollowupsCritic::find($id);
        $this->authorize("delete", $followupsCritic);
        $followupsCritic->delete();

        return response()->json($followupsCritic);
    }

    public function list(int $id)
    {
        $critics = FollowupsCritic::with('vet')->where('reception_id', $id)->get();

        return DataTables::of($critics)->make(true);
    }
}
