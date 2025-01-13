<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Http\Requests\BudgetRequest;
use App\Models\BudgetDetail;
use App\Models\Pet;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
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

        $this->authorize("create", Budget::class);
        return view('budget.create', compact('budget', 'pets', 'vets',));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BudgetRequest $request)
    {
        $this->authorize("create", Budget::class);
        $new = Budget::create($request->validated());
        $id = $new->id;

        return redirect()->route('budgets.edit', $id)
            ->with('success', 'Porfavor agrega los servicios para el presupuesto');
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
        $budgetDetail = new BudgetDetail();
        $products = Producto::where("ESTATUS",  "A")->get();
        $this->authorize("update", $budget);
        return view('budget.edit', compact('budget', 'pets', 'vets', 'budgetDetail', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BudgetRequest $request, Budget $budget)
    {
        $budget->update($request->validated());
        $this->authorize("update", $budget);
        return redirect()->route('budgets.index')
            ->with('success', 'Budget updated successfully');
    }

    public function destroy($id)
    {
        $budget = Budget::find($id);
        $this->authorize("delete", $budget);
        $budget->delete();
        return response()->json($budget);
    }

    public function list()
    {
        $budgets = Budget::with('vet', 'pet')->get();

        return DataTables::of($budgets)->make(true);
    }

    public function generatePdf(int $id)
    {
        $budget = Budget::with('pet', 'vet')->find($id);
        $details = BudgetDetail::with('service', 'serv')->where('budget_id', $id)->get();

        $pdf = Pdf::loadView("budget.pdf", compact("budget", 'details'));

        return $pdf->stream("PDF.pdf");
    }

    public function newtotal(BudgetRequest $request, int $id)
    {
        $budget = Budget::find($id);
        $budget->update($request->validated());
        return response()->json($budget);
    }

    public function budgetsign(int $id)
    {
        $budget = Budget::with('pet', 'vet')->find($id);
        $details = BudgetDetail::with('service', 'serv')->where('budget_id', $id)->get();

        return view('budget.pdf', compact("budget", "details"));
    }

    public function budgetpdf(Request $request, int $id)
    {
        $budget = Budget::with('pet', 'vet')->find($id);
        $details = BudgetDetail::with('service', 'serv')->where('budget_id', $id)->get();
        $signatureDataUrl = $request->input('signature');
        $signatureDataUrl2 = $request->input('signature2');

        $pdf = PDF::loadView('budget.pdf', [
            'budget' => $budget,
            'details' => $details,
            'signatureDataUrl' => $signatureDataUrl,
            'signatureDataUrl2' => $signatureDataUrl2,
            'isPdf' => true
        ]);

        $pdfPath = '/budgets/budget_' . $id . '.pdf';
        Storage::put('public' . $pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        return response()->json(['url' => asset('storage' . $pdfPath)]);
    }
}
