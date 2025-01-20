<?php

namespace App\Http\Controllers;

use App\Models\BudgetDetail;
use App\Http\Requests\BudgetDetailRequest;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class BudgetDetailController
 * @package App\Http\Controllers
 */
class BudgetDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgetDetails = BudgetDetail::paginate();

        return view('budget-detail.index', compact('budgetDetails'))
            ->with('i', (request()->input('page', 1) - 1) * $budgetDetails->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $budgetDetail = new BudgetDetail();
        return view('budget-detail.create', compact('budgetDetail'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BudgetDetailRequest $request)
    {
        $this->authorize("create", Budget::class);
        $new = BudgetDetail::create($request->validated());

        return response()->json($new);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $budgetDetail = BudgetDetail::find($id);

        return view('budget-detail.show', compact('budgetDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $budgetDetail = BudgetDetail::find($id);

        return view('budget-detail.edit', compact('budgetDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BudgetDetailRequest $request, BudgetDetail $budgetDetail)
    {
        $budgetDetail->update($request->validated());

        return redirect()->route('budget-details.index')
            ->with('success', 'BudgetDetail updated successfully');
    }

    public function destroy($id)
    {
        $budget = BudgetDetail::find($id);
        $budget->delete();

        return response()->json($budget);
    }

    public function list(int $id)
    {
        $groomings = BudgetDetail::with( 'serv', 'img', 'lab')->where('budget_id', $id)->get();

        return DataTables::of($groomings)->make(true);
    }

    public function price(int $id)
    {
        $price = DB::connection('firebird')
                ->table('ARTICULOS AS a')
                ->leftJoin('PRECIOS_ARTICULOS AS pa', 'a.ARTICULO_ID', '=', 'pa.ARTICULO_ID')
                ->leftJoin('CLAVES_ARTICULOS AS ca', 'a.ARTICULO_ID', '=', 'ca.ARTICULO_ID')
                ->where('a.ARTICULO_ID', $id)
                ->select('pa.PRECIO')
                ->first();

        return response()->json($price);
    }
}
