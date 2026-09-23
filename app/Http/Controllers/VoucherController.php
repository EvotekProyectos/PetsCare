<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Http\Requests\VoucherRequest;
use App\Models\AppointmentService;
use App\Models\Reception;
use App\Models\RedSheet;
use App\Models\VoucherProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class VoucherController
 * @package App\Http\Controllers
 */
class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", Voucher::class);
        $vouchers = Voucher::paginate();

        return view('voucher.index', compact('vouchers'))
            ->with('i', (request()->input('page', 1) - 1) * $vouchers->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $voucher = new Voucher();
        return view('voucher.create', compact('voucher'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoucherRequest $request)
    {
        Voucher::create($request->validated());

        return redirect()->route('vouchers.index')
            ->with('success', 'Voucher created successfully.');
    }

    /**
     * Display the specified resource. Devuelve JSON cuando el caller lo pide
     * (usado por el modal compartido de detalle, ver voucher/partials/detail-modal.blade.php),
     * o la vista de siempre para acceso directo por URL.
     */
    public function show($id)
    {
        $voucher = Voucher::with('voucherProducts.product')->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($voucher);
        }

        return view('voucher.show', compact('voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $voucher = Voucher::find($id);

        return view('voucher.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherRequest $request, Voucher $voucher)
    {
        $voucher->update($request->validated());

        return redirect()->route('vouchers.index')
            ->with('success', 'Voucher updated successfully');
    }

    public function destroy($id)
    {
        Voucher::find($id)->delete();

        return redirect()->route('vouchers.index')
            ->with('success', 'Voucher deleted successfully');
    }

    public function list()
    {
        $this->authorize("viewAny", Voucher::class);
        $vouchers = Voucher::with('reception', 'vet', 'voucherProducts', 'voucherProducts.product', 'reception.pet')->get();

        return DataTables::of($vouchers)->make(true);
    }

    public function listForReception($id)
    {
        $this->authorize("viewAny", Voucher::class);
        $vouchers = Voucher::with('reception', 'vet', 'voucherProducts', 'voucherProducts.product', 'reception.pet')
            ->where('reception_id', $id);

        return DataTables::of($vouchers)->make(true);
    }



    /**
     * Mapa de source_type (payload del frontend) -> modelo Eloquent origen
     * del producto a facturar. Extender aquí cuando se agregue Grooming.
     */
    private const SOURCE_MODELS = [
        'red_sheet' => RedSheet::class,
        'appointment_service' => AppointmentService::class,
    ];

    /**
     * Historial completo de vales de una fila puntual
     * (un RedSheet o AppointmentService), para el modal compartido de
     * detalle/historial (ver public/js/vouchers/detail-modal.js)
     */
    public function historyFor(Request $request)
    {
        $request->validate([
            'source_type' => 'required|in:' . implode(',', array_keys(self::SOURCE_MODELS)),
            'source_id' => 'required|integer',
        ]);

        $sourceModelClass = self::SOURCE_MODELS[$request->source_type];

        $vouchers = VoucherProduct::where('sourceable_id', $request->source_id)
            ->where('sourceable_type', $sourceModelClass)
            ->with('voucher:id,folio,status,cancellation_reason,rejection_reason,created_at')
            ->get()
            ->pluck('voucher')
            ->filter()
            ->values();

        return response()->json($vouchers);
    }

    public function storeProducts(Request $request)
    {
        $this->authorize("create", Voucher::class);

        $request->validate([
            'reception_id' => 'required|integer|exists:receptions,id',
            'source_type' => 'required|in:' . implode(',', array_keys(self::SOURCE_MODELS)),
            'source_ids' => 'required|array|min:1',
            'source_ids.*' => 'integer',
        ]);

        $voucher = Voucher::create([
            'reception_id' => $request->reception_id,
            'vet_id' => auth()->id(),
            'folio' => Voucher::buildFolio($request->reception_id)
        ]);

        $sourceModelClass = self::SOURCE_MODELS[$request->source_type];

        foreach ($request->source_ids as $sourceId) {
            $source = $sourceModelClass::find($sourceId);
            if (!$source) {
                continue;
            }

            $productId = $this->resolveProductId($request->source_type, $source);
            if (!$productId) {
                continue;
            }

            VoucherProduct::create([
                'voucher_id' => $voucher->id,
                'sourceable_id' => $sourceId,
                'sourceable_type' => $sourceModelClass,
                'product_id' => $productId,
            ]);
        }

        return response()->json([
            'success' => true,
            'voucher_id' => $voucher->id
        ]);
    }

    /**
     * El ARTICULO_ID a facturar vive en una columna distinta según el origen
     * (son mutuamente excluyentes por fila: solo una está poblada).
     */
    private function resolveProductId(string $sourceType, $source): ?int
    {
        return match ($sourceType) {
            'red_sheet' => $source->service_type_id ?? $source->imaging_type_id ?? $source->lab_type_id,
            'appointment_service' => $source->imaging_type_id ?? $source->lab_type_id,
            default => null,
        };
    }

    /**
     * Payload común para el modal de firma
     */
    private function voucherPayload(Voucher $voucher): array
    {
        $reception = $voucher->reception;

        return [
            'id' => $voucher->id,
            'folio' => $voucher->folio,
            'status' => $voucher->status,
            'vet' => $voucher->vet ? ['name' => $voucher->vet->name] : null,
            'reception' => $reception ? [
                'id' => $reception->id,
                'type' => $reception->receptionType->name ?? null,
            ] : null,
            'pet' => ['name' => $reception->pet->name ?? null],
            'family' => ['name' => $reception->family->name ?? null],
            'products' => $voucher->voucherProducts->map(fn ($vp) => [
                'id' => $vp->id,
                'name' => $vp->product->NOMBRE ?? '',
                'requested_quantity' => $vp->requested_quantity,
            ])->values(),
        ];
    }

    public function format(int $voucherId)
    {
        $this->authorize("create", Voucher::class);
        $voucher = Voucher::with(
            'reception',
            'vet',
            'voucherProducts',
            'voucherProducts.product',
            'reception.pet',
            'reception.family',
            'reception.receptionType'
        )->findOrFail($voucherId);

        return response()->json($this->voucherPayload($voucher));
    }

    public function generate(Request $request, $voucherId)
    {
        $this->authorize("create", Voucher::class);
        $voucher = Voucher::with(
            'reception',
            'vet',
            'voucherProducts.product',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        )->findOrFail($voucherId);

        // La cantidad ya no la captura el usuario: siempre es 1. El backend
        // es la fuente de verdad, no confía en ninguna cantidad enviada por
        // el cliente (aunque el frontend ya no la envía).
        VoucherProduct::where('voucher_id', $voucher->id)->update(['requested_quantity' => 1]);

        // Ya no se requiere firma del médico para generar el vale.

        // Generar PDF
        $pdf = Pdf::loadView('voucher.voucher', [
            'voucher' => $voucher->fresh(),
            'reception' => $voucher->reception,
            'isPdf' => true
        ]);

        $pdfName = 'vale_' . $voucher->folio . '.pdf';
        Storage::disk('public')->put('vouchers/' . $pdfName, $pdf->output());


        // Actualizar voucher
        $voucher->update([
            'generated_document' => 'vouchers/' . $pdfName,
            // 'issued_at' => now(),
            'status' => 'Pendiente'
        ]);

        return response()->json([
            'success' => true,
            'pdf_url' => asset('storage/vouchers/' . $pdfName)
        ]);
    }

    public function cancelFormat(int $voucherId)
    {
        $voucher = Voucher::with(
            'vet',
            'voucherProducts.product',
            'reception',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        )->findOrFail($voucherId);

        $this->authorize("cancel", $voucher);

        //solo se puede cancelar si está Pendiente
        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'message' => 'Este vale no puede ser cancelado.'
            ], 403);
        }

        return response()->json($this->voucherPayload($voucher));
    }

    public function cancel(Request $request, int $voucherId)
    {
        $voucher = Voucher::with('voucherProducts')->findOrFail($voucherId);
        $this->authorize("cancel", $voucher);

        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Este vale no puede ser cancelado.'
            ], 403);
        }

        // El médico ya no firma para cancelar, pero el motivo de cancelación
        // sigue siendo obligatorio.
        $request->validate([
            'observaciones' => 'required|string',
        ]);

        $voucher->update([
            'status'              => 'Cancelado',
            'cancellation_reason' => $request->observaciones,
            'cancelled_by'        => auth()->id(),
        ]);

        $voucher->load(
            'vet',
            'voucherProducts.product',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        );

        $pdf = Pdf::loadView('voucher.cancel', [
            'voucher'   => $voucher,
            'reception' => $voucher->reception,
            'isPdf'     => true
        ]);

        $pdfName = 'cancelacion_' . $voucher->folio . '.pdf';
        Storage::disk('public')->put('vouchers/' . $pdfName, $pdf->output());

        $voucher->update([
            'generated_document' => 'vouchers/' . $pdfName
        ]);

        return response()->json([
            'success' => true,
            'pdf_url' => asset('storage/vouchers/' . $pdfName)
        ]);
    }

    public function issueFormat(int $voucherId)
    {
        $voucher = Voucher::with(
            'vet',
            'voucherProducts.product',
            'reception',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        )->findOrFail($voucherId);

        $this->authorize("issue", $voucher);

        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'message' => 'Este vale no puede ser surtido.'
            ], 403);
        }

        return response()->json($this->voucherPayload($voucher));
    }

    public function issue(Request $request, int $voucherId)
    {
        $voucher = Voucher::with('voucherProducts')->findOrFail($voucherId);
        $this->authorize("issue", $voucher);

        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Este vale no puede ser surtido.'
            ], 403);
        }

        // El almacenista ya no firma ni agrega observaciones para surtir.
        $voucher->update([
            'status'     => 'Surtido',
            'issuer_id'  => auth()->id(),
            'issued_at'  => now(),
        ]);

        $voucher->load(
            'vet',
            'issuer',
            'voucherProducts.product',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        );

        $pdf = Pdf::loadView('voucher.issue', [
            'voucher'   => $voucher,
            'reception' => $voucher->reception,
            'isPdf'     => true
        ]);

        $pdfName = 'surtido_' . $voucher->folio . '.pdf';
        Storage::disk('public')->put('vouchers/' . $pdfName, $pdf->output());

        $voucher->update([
            'generated_document' => 'vouchers/' . $pdfName
        ]);

        return response()->json([
            'success' => true,
            'pdf_url' => asset('storage/vouchers/' . $pdfName)
        ]);
    }

    public function rejectFormat(int $voucherId)
    {
        $voucher = Voucher::with(
            'vet',
            'voucherProducts.product',
            'reception',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        )->findOrFail($voucherId);

        $this->authorize("reject", $voucher);

        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'message' => 'Este vale no puede ser rechazado.'
            ], 403);
        }

        return response()->json($this->voucherPayload($voucher));
    }

    public function reject(Request $request, int $voucherId)
    {
        $voucher = Voucher::with('voucherProducts')->findOrFail($voucherId);
        $this->authorize("reject", $voucher);

        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Este vale no puede ser rechazado.'
            ], 403);
        }

        // El almacenista ya no firma para rechazar, pero el motivo sigue
        // siendo obligatorio: es la única evidencia de por qué se rechazó.
        $request->validate([
            'observaciones' => 'required|string',
        ]);

        $voucher->update([
            'status'           => 'Rechazado',
            'rejection_reason' => $request->observaciones,
            'issuer_id'        => auth()->id(),
            'issued_at'        => now(),
        ]);

        $voucher->load(
            'vet',
            'voucherProducts.product',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        );

        $pdf = Pdf::loadView('voucher.reject', [
            'voucher'   => $voucher,
            'reception' => $voucher->reception,
            'isPdf'     => true
        ]);

        $pdfName = 'rechazo_' . $voucher->folio . '.pdf';
        Storage::disk('public')->put('vouchers/' . $pdfName, $pdf->output());

        $voucher->update([
            'generated_document' => 'vouchers/' . $pdfName
        ]);

        return response()->json([
            'success' => true,
            'pdf_url' => asset('storage/vouchers/' . $pdfName)
        ]);
    }

    //Actualizar tabla desde vista hospitilizacion
    public function lastUpdate(Request $request)
    {
        $lastUpdate = Voucher::where('reception_id', $request->reception_id)
            ->latest('updated_at')
            ->value('updated_at');

        return response()->json([
            'last_update' => $lastUpdate
        ]);
    }

    //Actualizar tabla vouchers
    public function lastUpdateGlobal()
    {
        $lastCreated = Voucher::latest('created_at')->value('created_at');
        $lastUpdated = Voucher::latest('updated_at')->value('updated_at');

        $lastChange = collect([$lastCreated, $lastUpdated])
            ->filter()
            ->max();

        return response()->json([
            'last_update' => $lastChange
        ]);
    }
}
