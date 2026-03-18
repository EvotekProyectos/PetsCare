<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Http\Requests\VoucherRequest;
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
     * Display the specified resource.
     */
    public function show($id)
    {
        $voucher = Voucher::find($id);

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
        $this->authorize("view", Voucher::class);
        $vouchers = Voucher::with('reception', 'vet', 'voucherProducts', 'voucherProducts.product', 'reception.pet')
            ->where('reception_id', $id);

        return DataTables::of($vouchers)->make(true);
    }



    public function storeProducts(Request $request)
    {

        $this->create("create", Voucher::class);
        $voucher = Voucher::create([
            'reception_id' => $request->reception_id,
            'vet_id' => auth()->id(),
            'folio' => Voucher::buildFolio($request->reception_id)
        ]);


        foreach ($request->redsheets as $redsheetId) {

            $redsheet = RedSheet::find($redsheetId);

            VoucherProduct::create([
                'voucher_id' => $voucher->id,
                'red_sheet_id' => $redsheetId,
                'product_id' => $redsheet->service_type_id
            ]);

            // $redsheet->update([
            //     'add_voucher' => 1
            // ]);
        }

        return response()->json([
            'success' => true,
            'voucher_id' => $voucher->id
        ]);
    }

    public function format(int $voucherId)
    {
        $this->create("create", Voucher::class);
        $voucher = Voucher::with(
            'reception',
            'vet',
            'voucherProducts',
            'voucherProducts.product',
            'reception.pet',
            'reception.family',
            'reception.receptionType'
        )->findOrFail($voucherId);

        $reception = $voucher->reception;

        return view('voucher.voucher', compact("voucher", "reception"));
    }

    public function generate(Request $request, $voucherId)
    {
        $this->create("create", Voucher::class);
        $voucher = Voucher::with(
            'reception',
            'vet',
            'voucherProducts.product',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        )->findOrFail($voucherId);

        // Guardar cantidades
        foreach ($request->cantidad as $redsheetId => $cantidad) {
            VoucherProduct::where('voucher_id', $voucher->id)
                ->where('red_sheet_id', $redsheetId)
                ->update(['requested_quantity' => $cantidad]);
        }

        // Guardar firma
        $image = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->signature);
        $signatureName = 'firma_medico_' . $voucher->id . '.png';
        Storage::disk('public')->put('signatures/' . $signatureName, base64_decode($image));

        $voucher->update(['vet_signature' => 'signatures/' . $signatureName]);

        // Generar PDF
        $pdf = Pdf::loadView('voucher.voucher', [
            'voucher' => $voucher->fresh(),
            'reception' => $voucher->reception,
            'isPdf' => true
        ]);

        $pdfName = 'vale_' . $voucher->folio . '.pdf';
        Storage::disk('public')->put('vouchers/' . $pdfName, $pdf->output());


        //Actualizar add_voucher cuando ya se haya firmado
        foreach ($voucher->voucherProducts as $voucherProduct) {
            RedSheet::where('id', $voucherProduct->red_sheet_id)
                ->update(['add_voucher' => 1]);
        }

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
        $this->create("cancel", Voucher::class);
        $voucher = Voucher::with(
            'vet',
            'voucherProducts.product',
            'reception',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        )->findOrFail($voucherId);

        //solo se puede cancelar si está Pendiente
        if ($voucher->status !== 'Pendiente') {
            abort(403, 'Este vale no puede ser cancelado.');
        }

        $reception = $voucher->reception;

        return view('voucher.cancel', compact('voucher', 'reception'));
    }

    public function cancel(Request $request, int $voucherId)
    {
        $this->create("cancel", Voucher::class);
        $voucher = Voucher::with('voucherProducts')->findOrFail($voucherId);

        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Este vale no puede ser cancelado.'
            ], 403);
        }

        $image = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->signature);
        $signatureName = 'firma_cancelacion_' . $voucherId . '_' . time() . '.png';
        Storage::disk('public')->put('signatures/' . $signatureName, base64_decode($image));

        $voucher->update([
            'status'                 => 'Cancelado',
            'cancellation_reason'    => $request->observaciones,
            'cancellation_signature' => 'signatures/' . $signatureName,
            'cancelled_by'           => auth()->id(),
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

        foreach ($voucher->voucherProducts as $voucherProduct) {
            RedSheet::where('id', $voucherProduct->red_sheet_id)
                ->update(['add_voucher' => 0]);
        }

        return response()->json([
            'success' => true,
            'pdf_url' => asset('storage/vouchers/' . $pdfName)
        ]);
    }

    public function issueFormat(int $voucherId)
    {
        $this->create("issue", Voucher::class);

        $voucher = Voucher::with(
            'vet',
            'voucherProducts.product',
            'reception',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        )->findOrFail($voucherId);

        if ($voucher->status !== 'Pendiente') {
            abort(403, 'Este vale no puede ser surtido.');
        }

        $reception = $voucher->reception;

        return view('voucher.issue', compact('voucher', 'reception'));
    }

    public function issue(Request $request, int $voucherId)
    {
        $this->create("issue", Voucher::class);
        $voucher = Voucher::with('voucherProducts')->findOrFail($voucherId);

        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Este vale no puede ser surtido.'
            ], 403);
        }

        $image = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->signature);
        $signatureName = 'firma_surtido_' . $voucherId . '_' . time() . '.png';
        Storage::disk('public')->put('signatures/' . $signatureName, base64_decode($image));

        $voucher->update([
            'status'                  => 'Surtido',
            'warehouse_observations'  => $request->observaciones,
            'warehouse_signature'        => 'signatures/' . $signatureName,
            'issuer_id'               => auth()->id(),
            'issued_at'               => now(),
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
        $this->create("reject", Voucher::class);
        $voucher = Voucher::with(
            'vet',
            'voucherProducts.product',
            'reception',
            'reception.pet',
            'reception.receptionType',
            'reception.family'
        )->findOrFail($voucherId);

        if ($voucher->status !== 'Pendiente') {
            abort(403, 'Este vale no puede ser rechazado.');
        }

        $reception = $voucher->reception;

        return view('voucher.reject', compact('voucher', 'reception'));
    }

    public function reject(Request $request, int $voucherId)
    {
        $this->create("reject", Voucher::class);
        $voucher = Voucher::with('voucherProducts')->findOrFail($voucherId);

        if ($voucher->status !== 'Pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Este vale no puede ser rechazado.'
            ], 403);
        }

        $image = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->signature);
        $signatureName = 'firma_rechazo_' . $voucherId . '_' . time() . '.png';
        Storage::disk('public')->put('signatures/' . $signatureName, base64_decode($image));

        $voucher->update([
            'status'             => 'Rechazado',
            'rejection_reason'   => $request->observaciones,
            'warehouse_signature' => 'signatures/' . $signatureName,
            'issuer_id'        => auth()->id(),
            'issued_at'               => now(),
        ]);

        foreach ($voucher->voucherProducts as $voucherProduct) {
            RedSheet::where('id', $voucherProduct->red_sheet_id)
                ->update(['add_voucher' => 0]);
        }

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
