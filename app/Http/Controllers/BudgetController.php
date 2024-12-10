<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Http\Requests\BudgetRequest;
use App\Models\Pet;
use App\Models\SurgeryPack;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class BudgetController
 * @package App\Http\Controllers
 */
class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = Budget::paginate();
        $this->authorize("viewAny", Budget::class);
        return view('budget.index', compact('budgets'))
            ->with('i', (request()->input('page', 1) - 1) * $budgets->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $budget = new Budget();
        $pets = Pet::all();
        $vets = User::all();
        $packs = SurgeryPack::all();

        $this->authorize("create", Budget::class);
        return view('budget.create', compact('budget','pets','vets','packs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BudgetRequest $request)
    {
        $this->authorize("create", Budget::class);
        Budget::create($request->validated());

        return redirect()->route('budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $budget = Budget::find($id);
        $this->authorize("view", $budget);
        return view('budget.show', compact('budget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $budget = Budget::find($id);
        $pets = Pet::all();
        $vets = User::all();
        $packs = SurgeryPack::all();
        $this->authorize("update", $budget );
        return view('budget.edit', compact('budget','pets','vets','packs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BudgetRequest $request, Budget $budget)
    {
        $budget->update($request->validated());
        $this->authorize("update", $budget );
        return redirect()->route('budgets.index')
            ->with('success', 'Budget updated successfully');
    }

    public function destroy($id)
    {
        $budget = Budget::find($id);
        $this->authorize("delete", $budget );
        $budget->delete();
        return response()->json($budget);
    }

    public function list()
    {
        $budgets = Budget::with('vet','pet','surgeryPack')->get();

        return DataTables::of($budgets)->make(true);
    }
}
