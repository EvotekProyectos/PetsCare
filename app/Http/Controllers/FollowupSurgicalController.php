<?php

namespace App\Http\Controllers;

use App\Models\FollowupSurgical;
use App\Http\Requests\FollowupSurgicalRequest;

/**
 * Class FollowupSurgicalController
 * @package App\Http\Controllers
 */
class FollowupSurgicalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $followupSurgicals = FollowupSurgical::paginate();
        $this->authorize("viewAny",FollowupSurgical::class);
        return view('followup-surgical.index', compact('followupSurgicals'))
            ->with('i', (request()->input('page', 1) - 1) * $followupSurgicals->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $followupSurgical = new FollowupSurgical();
        $this->authorize("create",FollowupSurgical::class);
        return view('followup-surgical.create', compact('followupSurgical'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FollowupSurgicalRequest $request)
    {
        FollowupSurgical::create($request->validated());
        $this->authorize("create",FollowupSurgical::class);
        return redirect()->route('followup-surgicals.index')
            ->with('success', 'FollowupSurgical created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $followupSurgical = FollowupSurgical::find($id);
        $this->authorize("viewAny", $followupSurgical);
        return view('followup-surgical.show', compact('followupSurgical'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $followupSurgical = FollowupSurgical::find($id);
        $this->authorize("update", $followupSurgical);
        return view('followup-surgical.edit', compact('followupSurgical'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FollowupSurgicalRequest $request, FollowupSurgical $followupSurgical)
    {
        $followupSurgical->update($request->validated());
        $this->authorize("update", $followupSurgical);
        return redirect()->route('followup-surgicals.index')
            ->with('success', 'FollowupSurgical updated successfully');
    }

    public function destroy($id)
    {
        FollowupSurgical::find($id)->delete();
        //$this->authorize("delete",FollowupSurgical::class);
        return redirect()->route('followup-surgicals.index')
            ->with('success', 'FollowupSurgical deleted successfully');
    }
}
