<?php

namespace App\Http\Controllers;

use App\Models\FollowupIntern;
use App\Http\Requests\FollowupInternRequest;
use App\Models\ReceptionEvent;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class FollowupInternController
 * @package App\Http\Controllers
 */
class FollowupInternController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $followupInterns = FollowupIntern::paginate();
        $this->authorize("viewAny",FollowupIntern::class);
        return view('followup-intern.index', compact('followupInterns'))
            ->with('i', (request()->input('page', 1) - 1) * $followupInterns->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $followupIntern = new FollowupIntern();
        $this->authorize("create",FollowupIntern::class);
        return view('followup-intern.create', compact('followupIntern'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FollowupInternRequest $request)
    {
        $new = FollowupIntern::create($request->validated());
        $this->authorize("create",FollowupIntern::class);

        if ($new->reception_id) {
            ReceptionEvent::create([
                'reception_id' => $new->reception_id,
                'event_type' => 'followup_added',
                'description' => 'Seguimiento interno agregado',
                'followup_type' => 'followup_intern',
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
        $followupIntern = FollowupIntern::find($id);
        $this->authorize("viewAny", $followupIntern);
        return view('followup-intern.show', compact('followupIntern'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $followupIntern = FollowupIntern::find($id);
        $this->authorize("update", $followupIntern);
        return view('followup-intern.edit', compact('followupIntern'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FollowupInternRequest $request, FollowupIntern $followupIntern)
    {
        $followupIntern->update($request->validated());
        $this->authorize("update", $followupIntern);
        $reception = $request->reception_id;
        
        return redirect()->route('hospitalization.followups', $reception)
            ->with('success', 'Seguimiento Editado');
    }

    public function destroy($id)
    {
        $followupIntern = FollowupIntern::find($id);
        $this->authorize("delete",$followupIntern);
        $followupIntern->delete();

        return response()->json($followupIntern);
    }

    public function list(int $id)
    {
        $critics = FollowupIntern::with('vet')->where('reception_id', $id)->get();

        return DataTables::of($critics)->make(true);
    }
}
