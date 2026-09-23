<?php

namespace App\Http\Controllers;

use App\Models\Surgery;
use App\Http\Requests\SurgeryRequest;
use App\Models\Format;
use App\Models\Hospitalization;
use App\Models\Pet;
use App\Models\ProductClassification;
use App\Models\Producto;
use App\Models\ProductType;
use App\Models\Reception;
use App\Models\ReceptionTransfer;
use App\Models\RedSheet;
use App\Models\User;
use App\Services\ReceptionDocumentService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class SurgeryController
 * @package App\Http\Controllers
 */
class SurgeryController extends Controller
{
    private ReceptionDocumentService $documentService;

    public function __construct(ReceptionDocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surgeries = Surgery::paginate();
        $this->authorize("viewAny", Surgery::class);
        return view('surgery.index', compact('surgeries'))
            ->with('i', (request()->input('page', 1) - 1) * $surgeries->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $surgery = new Surgery();
        $products = ProductType::all();
        $reception = Reception::find($id);
        $this->authorize("create", Surgery::class);

        return view('surgery.create', compact('surgery',  'products', 'reception'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurgeryRequest $request)
    {
        $this->authorize("create", Surgery::class);
        $this->guardReceptionNotTransferred(Reception::findOrFail($request->reception_id));

        $validated = $request->validated();

        // Modal de registro rápido de cirugía (red-sheet/create.blade.php,
        // ModalSurgeries): ahí #date va oculto y la fecha siempre debe ser
        // la actual del servidor, sin depender de lo que haya llegado del
        // navegador. use_current_date solo lo manda ese formulario — el de
        // surgery.create/edit (mismo endpoint) no lo envía y conserva su
        // propio campo de fecha capturable tal cual funciona hoy.
        if ($request->boolean('use_current_date')) {
            $validated['date'] = now();
        }

        $surgery = new Surgery($validated);
        $surgery->vet_id = auth()->id();
        $surgery->save();

        return response()->json($surgery);
        // return redirect()->route('surgeries.index')
        //     ->with('success', 'Surgery created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surgery = Surgery::find($id);

        return view('surgery.show', compact('surgery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $surgery = Surgery::find($id);
        $reception = $surgery->reception;
        $vets = $surgery->user;
        $products = ProductType::all();

        $this->authorize("update", $surgery);

        return view('surgery.edit', compact('surgery', 'reception', 'products', 'vets'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(SurgeryRequest $request, Surgery $surgery)
    {
        $surgery->update($request->validated());

        return redirect()->route('surgeries.index')
            ->with('success', 'Surgery updated successfully');
    }

    public function destroy($id)
    {
        Surgery::find($id)->delete();

        return redirect()->route('surgeries.index')
            ->with('success', 'Surgery deleted successfully');
    }

    public function entry($id)
    {
        $surgery = Surgery::with('vet', 'surgery')->where('reception_id', $id)->get();

        return DataTables::of($surgery)->make(true);
    }

    public function checkRequirements($id)
    {
        $hasLab = RedSheet::where('reception_id', $id)
            ->whereNotNull('lab_type_id')
            ->exists();

        $hasImaging = RedSheet::where('reception_id', $id)
            ->whereNotNull('imaging_type_id')
            ->exists();

        if ($hasLab && $hasImaging) {
            return response()->json(['status' => 'ok']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Se requiere al menos un registro de laboratorio y uno de imagenología.']);
        }
    }


    public function surgery_authorization($id)
    {
        // Ya fue firmada: no volver a mostrar la responsiva en blanco. Mismo
        // criterio que ReceptionController::hospital_authorization() (Format
        // format_type_id=3 ligado a esta reception, creado en
        // surgery_authorizationpdf() al aceptar y firmar).
        $alreadyAuthorized = Format::where('reception_id', $id)
            ->where('format_type_id', 3)
            ->exists();

        // Distingue si esta responsiva se abrió desde el modal de Documentos
        // de Recepción (ver ReceptionController::documents()/
        // requiredFormatsFor(), que arma esta URL con ?from=reception) o
        // desde el flujo propio de Hospital (hospital_auth.js encadenando
        // aquí tras firmar la autorización de hospitalización en área
        // Quirúrgicos). En el primer caso la recepcionista siempre debe
        // volver a Recepciones, nunca a Hospitalizaciones (assignment.hospital,
        // panel exclusivo de médico) — mismo criterio ya aplicado en
        // hospital_authorization().
        $fromReception = request()->query('from') === 'reception';

        if ($alreadyAuthorized) {
            $cameFromTransfer = ReceptionTransfer::where('to_reception_id', $id)->exists();

            // Mismo destino que auth_surgery.js elige al terminar de firmar.
            return redirect()
                ->route($fromReception || !$cameFromTransfer ? 'receptions.index' : 'assignment.hospital')
                ->with('success', 'Esta cirugía ya cuenta con su autorización firmada.');
        }

        $reception = Reception::find($id);

        $products = DB::connection('firebird')
            ->table('ARTICULOS AS a')
            ->leftJoin('PRECIOS_ARTICULOS AS pa', 'a.ARTICULO_ID', '=', 'pa.ARTICULO_ID')
            ->where('a.ESTATUS', 'A')
            ->select('a.ARTICULO_ID', 'a.NOMBRE', 'pa.PRECIO')
            ->get();

        $pet = Pet::with('family', 'genre')->find($id);

        $cameFromTransfer = ReceptionTransfer::where('to_reception_id', $id)->exists();

        // Sin esto, el botón "Atrás" del navegador puede restaurar esta
        // página (el formulario en blanco) desde su caché/bfcache SIN volver
        // a pedírsela al servidor — la condición de arriba nunca se vuelve a
        // evaluar y parece que "no se actualizó". no-store fuerza a que
        // siempre se re-consulte.
        return response()
            ->view('surgery.aut_quirurgica', compact("reception", "pet", "products", "cameFromTransfer", "fromReception"))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function surgery_authorizationpdf(Request $request, $id)
    {
        $reception = Reception::with('pet')->find($id);
        $pet = $reception->pet;


        $signatureDataUrl = $request->input('signature');
        $procedure = $request->input('procedure');
        $total = $request->input('total');
        $include = $request->input('include');

        $pdf = PDF::loadView('surgery.aut_quirurgica', [
            'reception' => $reception,
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
            'procedure' => $procedure,
            'total' => $total,
            'include' => $include,
            'isPdf' => true
        ]);

        $pdfPath = 'public/hospitalizations/auth_surgery_' . $id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        $format = new Format();
        $format->format_type_id = 3;
        $format->reception_id = $id;
        $format->pet_id = $pet->id;
        $format->format_pdf = $pdfPath;
        $format->save();

        // Firmar la quirúrgica también puede ser lo que finalmente admite
        // al paciente (si se firmó después de la de Hospital, o si se firmó
        // primero y la de Hospital ya existía) — mismo método que usa
        // ReceptionController::hospital_authorizationpdf(), no se duplica
        // la regla de "cuándo pasar a Hospitalizado".
        $this->documentService->advanceToHospitalizadoIfComplete($reception);

        // Si a esta recepción todavía le falta otra responsiva requerida
        // (ver ReceptionDocumentService::nextMissingFormat() — típicamente
        // la Autorización de Hospital, si esta quirúrgica se firmó primero
        // desde el modal de Documentos), el frontend encadena directo a
        // firmarla en vez de volver a Recepciones (ver auth_surgery.js).
        $nextFormat = $this->documentService->nextMissingFormat($reception);
        $nextFormatUrl = $nextFormat
            ? route($nextFormat['route'], ['id' => $id, 'from' => 'reception'])
            : null;

        return response()->json([
            'url' => asset($pdfUrl),
            'format_id' => $format->id,
            'next_format_url' => $nextFormatUrl,
        ]);
    }
}
