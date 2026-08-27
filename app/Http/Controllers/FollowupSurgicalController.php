<?php

namespace App\Http\Controllers;

use App\Models\FollowupSurgical;
use App\Http\Requests\FollowupSurgicalRequest;
use App\Models\ReceptionEvent;
use Yajra\DataTables\Facades\DataTables;

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
        $new = FollowupSurgical::create($request->validated());
        $this->authorize("create",FollowupSurgical::class);

        if ($new->reception_id) {
            ReceptionEvent::create([
                'reception_id' => $new->reception_id,
                'event_type' => 'followup_added',
                'description' => 'Seguimiento quirúrgico agregado',
                'followup_type' => 'followup_surgical',
                'followup_id' => $new->id,
                'created_by' => auth()->id(),
            ]);
        }

        return response()->json($new);
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
        $reception = $request->reception_id;
        
        return redirect()->route('hospitalization.followups', $reception)
            ->with('success', 'Seguimiento Editado');
    }

    public function destroy($id)
    {
        $followupSurgical = FollowupSurgical::find($id);
        $this->authorize("delete",$followupSurgical);
        $followupSurgical->delete();
        
        return response()->json($followupSurgical);
    }

    public function list(int $id)
    {
        $critics = FollowupSurgical::with('vet')->where('reception_id', $id)->get();

        return DataTables::of($critics)->make(true);
    }
}
