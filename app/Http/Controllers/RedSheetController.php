<?php

namespace App\Http\Controllers;

use App\Models\RedSheet;
use App\Http\Requests\RedSheetRequest;
use App\Models\FollowUp;
use App\Models\ProductType;
use App\Models\Reception;
use App\Models\Surgery;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class RedSheetController
 * @package App\Http\Controllers
 */
class RedSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $redSheets = RedSheet::paginate();
        $this->authorize("viewAny", RedSheet::class);

        return view('red-sheet.index', compact('redSheets'))
            ->with('i', (request()->input('page', 1) - 1) * $redSheets->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $redSheet = new RedSheet();
        $this->authorize("create", RedSheet::class);
        return view('red-sheet.create', compact('redSheet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RedSheetRequest $request)
    {
        $new = RedSheet::create($request->validated());
        $this->authorize("create", RedSheet::class);

        return response()->json($new);

        // return redirect()->route('red-sheets.index')
        //     ->with('success', 'RedSheet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("view", $redSheet);

        return view('red-sheet.show', compact('redSheet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("update", $redSheet);

        return view('red-sheet.edit', compact('redSheet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RedSheetRequest $request, RedSheet $redSheet)
    {
        $redSheet->update($request->validated());
        $this->authorize("update", $redSheet);

        return redirect()->route('red-sheets.index')
            ->with('success', 'RedSheet updated successfully');
    }

    public function destroy($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("update", $redSheet);
        $redSheet->delete();

        return response()->json($redSheet);
    }

    public function entry($id)
    {
        $redSheet = new RedSheet();
        $reception = Reception::with('pet', 'admissionType', 'area')->findorfail($id);
        $products = ProductType::all();
        $followUp = new FollowUp();
        $surgery = new Surgery();
        $this->authorize("create", RedSheet::class);
        return view('red-sheet.create', compact('redSheet', 'reception', 'products', 'followUp', 'surgery'));
    }

    public function recap(int $id)
    {
        $redsheets = RedSheet::with('vet', 'imaging', 'lab', 'service')->where('reception_id', $id)->get();

        return DataTables::of($redsheets) ->make(true);

    }
}
