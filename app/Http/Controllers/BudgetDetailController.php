<?php

namespace App\Http\Controllers;

use App\Models\BudgetDetail;
use App\Http\Requests\BudgetDetailBatchRequest;
use App\Http\Requests\BudgetDetailRequest;
use App\Models\Budget;
use App\Models\Reception;
use Illuminate\Support\Facades\Cache;
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
     * Guarda en una sola petición todas las líneas (servicio/lab/img) agregadas
     * en el modal de presupuesto de Consulta.
     *
     * Un mismo Reception puede tener VARIOS presupuestos independientes a lo
     * largo del tiempo (ver receptionHistory()/"Presupuestos de esta
     * consulta"): cada apertura del modal es una sesión de captura nueva y
     * debe crear su PROPIO Budget, nunca reutilizar uno de una sesión
     * anterior -antes se hacía Budget::firstOrCreate(['reception_id' =>
     * ...]), que encontraba y reutilizaba el primer Budget que existiera
     * para esa recepción sin importar cuántas sesiones hubiera habido desde
     * entonces, mezclando los conceptos de todos los presupuestos de la
     * consulta bajo un solo budget_id-. $request->budget_id (ver
     * BudgetDetailBatchRequest) es null en el primer lote de cada sesión
     * (appointment-modal.js resetea BudgetId a null al abrir/cerrar el
     * modal) y ya trae el id devuelto aquí en los lotes siguientes de la
     * MISMA sesión, para que sí se acumulen en el mismo Budget mientras el
     * modal sigue abierto.
     */
    public function storeBatch(BudgetDetailBatchRequest $request)
    {
        $this->authorize("create", Budget::class);

        $reception = Reception::findOrFail($request->reception_id);

        $budget = DB::transaction(function () use ($request, $reception) {
            if ($request->filled('budget_id')) {
                // where('reception_id', ...) además del id: nunca se debe
                // poder seguir agregando conceptos a un Budget de OTRA
                // recepción por un budget_id manipulado desde el cliente.
                $budget = Budget::where('id', $request->budget_id)
                    ->where('reception_id', $reception->id)
                    ->firstOrFail();
            } else {
                $budget = Budget::create([
                    'reception_id' => $reception->id,
                    'pet_id' => $reception->pet_id,
                    'vet_id' => $reception->veterinarian_id,
                    'date' => now(),
                ]);
            }

            foreach ($request->lines as $line) {
                $data = [
                    'budget_id' => $budget->id,
                    'price' => $line['price'],
                    'notes' => $line['notes'] ?? null,
                ];

                $data[match ($line['type']) {
                    'service' => 'service_id',
                    'lab' => 'lab_id',
                    'img' => 'img_id',
                }] = $line['product_id'];

                BudgetDetail::create($data);
            }

            return $budget;
        });

        return response()->json(['budget_id' => $budget->id]);
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
        // orderBy('id'): no existe una columna de orden/posición propia en
        // budget_details (ver migración), y el id autoincremental sí
        // representa de forma confiable el orden real de inserción. Sin
        // esto, DataTables (order: [] en appointment-modal.js, para NO
        // reordenar) mostraba lo que MySQL devolviera sin garantía de orden.
        $groomings = BudgetDetail::with('serv', 'img', 'lab')
            ->where('budget_id', $id)
            ->orderBy('id')
            ->get();

        return DataTables::of($groomings)->make(true);
    }

    /**
     * El costo real aquí no es la consulta en sí (1 fila por PK) sino la
     * conexión a Firebird en cada request (~2.2s medido) — mismo problema que
     * ya se corrigió en Producto::where(ESTATUS,A) para AppointmentController.
     * Se cachea por producto (el precio de Microsip no cambia segundo a
     * segundo) para que la 2a vez que se elija el mismo servicio sea
     * instantánea; la 1a vez sigue pagando la conexión, eso no se puede
     * evitar sin cambiar cómo se conecta a Firebird.
     */
    public function price(int $id)
    {
        $price = Cache::remember("budget_product_price_{$id}", 300, function () use ($id) {
            return DB::connection('firebird')
                ->table('ARTICULOS AS a')
                ->leftJoin('PRECIOS_ARTICULOS AS pa', 'a.ARTICULO_ID', '=', 'pa.ARTICULO_ID')
                ->where('a.ARTICULO_ID', $id)
                ->select('pa.PRECIO')
                ->first();
        });

        return response()->json($price);
    }
}
