<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Http\Requests\FollowUpRequest;

/**
 * Class FollowUpController
 * @package App\Http\Controllers
 */
class FollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $followUps = FollowUp::paginate();
        $this->authorize("viewAny", FollowUp::class);

        return view('follow-up.index', compact('followUps'))
            ->with('i', (request()->input('page', 1) - 1) * $followUps->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $followUp = new FollowUp();
        $this->authorize("create", FollowUp::class);
        return view('follow-up.create', compact('followUp'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FollowUpRequest $request)
    {
        $new = FollowUp::create($request->validated());
        $this->authorize("create", FollowUp::class);

        return response()->json($new);

        // return redirect()->route('follow-ups.index')
        //     ->with('success', 'FollowUp created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $followUp = FollowUp::find($id);
        $this->authorize("view", $followUp);

        return view('follow-up.show', compact('followUp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $followUp = FollowUp::find($id);
        $this->authorize("update", $followUp);

        return view('follow-up.edit', compact('followUp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FollowUpRequest $request, FollowUp $followUp)
    {
        $followUp->update($request->validated());
        $this->authorize("update", $followUp);

        return redirect()->route('follow-ups.index')
            ->with('success', 'FollowUp updated successfully');
    }

    public function destroy($id)
    {
        $followUp = FollowUp::find($id);
        $this->authorize("delete", $followUp);
        $followUp->delete();

        return redirect()->route('follow-ups.index')
            ->with('success', 'FollowUp deleted successfully');
    }
}
