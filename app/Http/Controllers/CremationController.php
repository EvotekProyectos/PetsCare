<?php

namespace App\Http\Controllers;

use App\Models\Cremation;
use App\Http\Requests\CremationRequest;
use App\Models\CmType;
use App\Models\CremationStatus;
use App\Models\CremationStatusHistory;
use App\Models\Folio;
use App\Models\Format;
use App\Models\FormatType;
use App\Models\GenericModel;
use App\Models\PaymentOrder;
use App\Models\Pet;
use App\Models\Producto;
use App\Models\Reception;
use App\Models\User;
use App\Services\AccountStatementService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CremationController
 * @package App\Http\Controllers
 */
class CremationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cremations = Cremation::paginate();
        $this->authorize("viewAny", Cremation::class);
        return view('cremation.index', compact('cremations'))
            ->with('i', (request()->input('page', 1) - 1) * $cremations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cremation = new Cremation();
        $cremation->status = 'En espera de realizar';
        $cms = CmType::all();

        $vets = User::all();
        $this->authorize("create", Cremation::class);
        return view('cremation.create', compact('cremation', 'cms', 'vets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CremationRequest $request)
    {
        $this->authorize("create", Cremation::class);
        $new = Cremation::create($request->validated());

        Pet::where('id', $request->pet_id)->update(['deceased' => 1]);

        // "Creado": pendiente de pago, todavía no se puede empezar a
        // trabajar el servicio.
        CremationStatusHistory::create([
            'reception_id' => $new->reception_id,
            'cremation_status_id' => CremationStatus::where('name', 'Creado')->value('id'),
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        return response()->json($new);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cremation = Cremation::find($id);
        $this->authorize("view", Cremation::class);
        return view('cremation.show', compact('cremation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cremation = Cremation::find($id);
        $reception = $cremation->reception;
        $cms = CmType::all();
        $vets = User::all();
        $products = Producto::where("ESTATUS",  "A")->get();

        $this->authorize("update", $cremation);
        return view('cremation.edit', compact('cremation', 'reception', 'cms', 'products', 'vets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CremationRequest $request, Cremation $cremation)
    {
        $cremation->update($request->validated());
        $this->authorize("update", $cremation);
        return redirect()->route('cremations.index')
            ->with('success', 'Cremación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $cremation = Cremation::find($id);
        $this->authorize("delete", $cremation);
        $cremation->delete();

        return response()->json($cremation);
        //return redirect()->route('cremations.index')
        //  ->with('success', 'Cremation deleted successfully');
    }


    public function new(int $id)
    {
        $cremation = new Cremation();
        $cremation->status = "En espera de realizar";
        $vets = User::all();
        $reception = Reception::with('pet', 'reason', 'vet', 'receptionist')->findorfail($id);
        $cms = CmType::all();
        $products = Producto::where("ESTATUS",  "A")->get();

        return view('cremation.create', compact('cremation', 'reception', 'cms', 'products', 'vets'));
    }

    public function comprobante(int $id)
    {
        $cremation = Cremation::with("reception", "pet", "serv", "service")->find($id);
        $reception = Reception::with("payment")->find($cremation->reception_id);

        //$next=Appointment::where("reception_id", $reception)->get()->First();       
        $pdf = Pdf::loadView("cremation.comprobante", compact("cremation", "reception"));
        return $pdf->stream('comprobante.pdf');
    }


    public function list(Request $request)
    {
        $cremations = Cremation::with('reception', 'reception.receptionist', 'reception.family', 'pet', 'cm', 'tag', 'serv', 'vet')
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereHas('reception', function ($q) use ($request) {
                    $q->whereDate('entry_date', $request->date);
                });
            })
            ->when($request->filled('status_id'), function ($query) use ($request) {
                $query->where('status', $request->status_id);
            })
            ->get();
        return response()->json($cremations);
    }

    public function updateStatus($id, Request $request)
    {
        $cremation = Cremation::findOrFail($id);
        $cremation->status = $request->status;
        $cremation->save();

        return response()->json(['success' => true, 'status' => $cremation->status]);
    }

    public function responsiva(int $id)
    {

        $reception = Reception::with('family', 'pet')->find($id);
        $cremation = Cremation::where('reception_id', $id)->first();

        return view('cremation.responsiva', compact("cremation", "reception"));
    }

    public function responsivaPdf(Request $request, $id)
    {
        $reception = Reception::with('family', 'pet')->find($id);
        $cremation = Cremation::where('reception_id', $id)->first();
        // $pet=$reception->pet;

        $signatureDataUrl = $request->input('signature');
        $nameFamily = $request->input('name_family');

        $pdf = PDF::loadView('cremation.responsiva', [
            'reception' => $reception,
            //   'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
            'nameFamily' => $nameFamily,
            'isPdf' => true
        ]);

        $pdfPath = 'public/cremations/responsiva_' . $id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        $format = new Format();
        $format->format_type_id = 7;
        $format->reception_id = $id;
        //   $format->pet_id = $pet->id;
        $format->format_pdf = $pdfPath;
        $format->save();


        return response()->json(['url' => asset($pdfUrl), 'format_id' => $format->id]);
        //return response()->json(['url' => $pdfUrl, 'format_id' => $format->id]);
        //     //return response()->json(['url' => asset('storage/'.$pdfPath), 'format_id' => $format->id]);
    }

    /**
     * Responsiva de entrega de cenizas. Firmarla es
     * lo único que avanza el estatus (CremationStatusHistory) a "Entregado".
     */
    public function entregaCenizas(int $id)
    {
        $reception = Reception::with('family', 'pet')->find($id);
        $cremation = Cremation::where('reception_id', $id)->first();

        return view('cremation.entrega_cenizas', compact('cremation', 'reception'));
    }

    public function entregaCenizasPdf(Request $request, $id)
    {
        $reception = Reception::with('family', 'pet')->find($id);
        $cremation = Cremation::where('reception_id', $id)->first();

        $signatureDataUrl = $request->input('signature');
        $nameFamily = $request->input('name_family');

        $pdf = PDF::loadView('cremation.entrega_cenizas', [
            'reception' => $reception,
            'cremation' => $cremation,
            'signatureDataUrl' => $signatureDataUrl,
            'nameFamily' => $nameFamily,
            'isPdf' => true,
        ]);

        $pdfPath = 'public/cremations/entrega_cenizas_' . $id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        $format = new Format();
        $format->format_type_id = FormatType::where('name', 'Entrega de cenizas')->value('id');
        $format->reception_id = $id;
        $format->pet_id = $reception->pet_id;
        $format->format_pdf = $pdfPath;
        $format->save();

        $entregadoStatusId = CremationStatus::where('name', 'Entregado')->value('id');
        CremationStatusHistory::create([
            'reception_id' => $id,
            'cremation_status_id' => $entregadoStatusId,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        return response()->json(['url' => asset($pdfUrl), 'format_id' => $format->id]);
    }

    public function ordenventa(int $reception)
    {
        // Foleador para las Ordenes del Punto de Venta
        $folio = Folio::select([
            'CONSECUTIVO',
        ])
            ->where('CAJA_ID', 170159)
            ->firstOrFail()->CONSECUTIVO;
        $newFolio = 'N' . str_pad($folio + 1, 8, '0', STR_PAD_LEFT);
        Folio::where('CAJA_ID', 170159)->increment('CONSECUTIVO', 1);

        //Variables para guardar los detalles y totales para el insert
        $listadoPartidas = [];
        $articulosDetalles = [];
        $importeNeto = 0;
        $now = Carbon::now();

        //Buscar Los servicios registrados a la recepción , son los id de productos en microsip
        $Ids = Cremation::where('reception_id', $reception)
            ->where(function ($query) {
                $query->whereNotNull('servicie');
            })
            ->pluck('servicie');

        //Recorrer Cada Servicio para sacar los detalleS que guardamos de la ODV y calculamos total
        foreach ($Ids as $articuloId) {
            //Query para Mircrosip
            $articuloDetalle = DB::connection('firebird')
                ->table('ARTICULOS AS a')
                ->leftJoin('PRECIOS_ARTICULOS AS pa', 'a.ARTICULO_ID', '=', 'pa.ARTICULO_ID')
                ->leftJoin('CLAVES_ARTICULOS AS ca', 'a.ARTICULO_ID', '=', 'ca.ARTICULO_ID')
                ->where('a.ARTICULO_ID', $articuloId)
                ->select('a.NOMBRE', 'a.ARTICULO_ID', 'pa.PRECIO', 'ca.CLAVE_ARTICULO')
                ->first();

            //Guardamos detalles de cada servico
            if ($articuloDetalle) {
                $articulosDetalles[] = $articuloDetalle;
            }

            //Calculo de total unitario 
            foreach ($articulosDetalles as $key => $producto) {
                $totalNetoProducto =  floatval($producto->PRECIO);
            }

            //Guardamos partidas para los futuros inserts de DOCTOS_PV_DET
            $listadoPartidas[] = [
                'CLAVE_ARTICULO' => $producto->CLAVE_ARTICULO,
                'ARTICULO_ID' => $producto->ARTICULO_ID,
                'UNIDADES' => 1,
                'UNIDADES_DEV' => 0,
                'TIPO_CONTAB_UNID' => 0,
                'PRECIO_UNITARIO' => $producto->PRECIO,
                'PRECIO_UNITARIO_IMPTO' => $producto->PRECIO,
                'IMPUESTO_POR_UNIDAD' => 0,
                'PCTJE_DSCTO' => 0,
                'PRECIO_TOTAL_NETO' => $totalNetoProducto,
                'PRECIO_MODIFICADO' => 'N',
                'PCTJE_COMIS' => 0,
                'ROL' => 'N',
                'POSICION' => $key + 1,
                'DSCTO_ART' => 0,
                'DSCTO_EXTRA' => 0,
            ];

            //Calculo total
            $importeNeto += $totalNetoProducto;
        }

        //Campos para el insert de DOCTOS_PV
        $ordenFields['CAJA_ID'] = 170159;
        $ordenFields['TIPO_DOCTO'] = 'O';
        $ordenFields['SUCURSAL_ID'] = 54057;
        $ordenFields['FOLIO'] = $newFolio;
        $ordenFields['FECHA'] = $now->format('Y-m-d');
        $ordenFields['HORA'] = $now->format('H:i:s');
        $ordenFields['CAJERO_ID'] = 170160;
        $ordenFields['CLIENTE_ID'] = 860;
        $ordenFields['ALMACEN_ID'] = 953;
        $ordenFields['MONEDA_ID'] = 1;
        $ordenFields['IMPUESTO_INCLUIDO'] = 'S';
        $ordenFields['TIPO_CAMBIO'] = 1;
        $ordenFields['TIPO_DSCTO'] = 'P';
        $ordenFields['DSCTO_PCTJE'] = 0;
        $ordenFields['DSCTO_IMPORTE'] = 0;
        $ordenFields['ESTATUS'] = 'N';
        $ordenFields['APLICADO'] = 'S';
        $ordenFields['SISTEMA_ORIGEN'] = 'PV';

        //Inicilaizar los modelos para las tablas a las cuales les haremos insert en base al generico
        $basePVModel = new GenericModel('DOCTOS_PV', 'DOCTO_PV_ID');
        $detPVModel = new GenericModel('DOCTOS_PV_DET', 'DOCTO_PV_DET_ID');

        //Desactivamos las timestamps
        $basePVModel->timestamps = false;
        $detPVModel->timestamps = false;

        //Insert para la cabecera de DOCTOS_PV y guardamos el ID generado
        $ordenId = $basePVModel->createGeneric($ordenFields);

        //Inserts de cada partida para la tabla DOCTOS_PV_DET
        foreach ($listadoPartidas as $partida) {
            $partida['DOCTO_PV_ID'] = $ordenId;

            $detPVModel->createGeneric($partida);
        }

        //Guardamos en nuestra BD el Folio Generado para control de los pagos
        $data = [
            'reception_id' => $reception,
            'folio_odv' => $newFolio
        ];
        PaymentOrder::create($data);


        //Regresamos el Folio de la ODV con el que pueden pasar a pagar a caja
        return response()->json($newFolio);
    }

    /**
     * Previsualización del estado de cuenta de una recepción de cremación:
     * NO crea ningún Charge, solo muestra lo que se cobraría si se cierra la cuenta.
     */
    public function accountStatement(int $reception, AccountStatementService $statementService)
    {
        $receptionModel = Reception::with(['pet.family', 'episode.account'])->findOrFail($reception);
        $this->authorize('update', $receptionModel);

        return response()->json($statementService->preview($receptionModel));
    }

    /**
     * PDF informativo del estado de cuenta (NO es la ODV de Microsip).
     */
    public function accountStatementPdf(int $reception, AccountStatementService $statementService)
    {
        $receptionModel = Reception::with('pet.family')->findOrFail($reception);
        $this->authorize('update', $receptionModel);

        $pdf = Pdf::loadView('account-statement.pdf', $statementService->pdfData($receptionModel));

        return $pdf->stream('estado-de-cuenta-' . $reception . '.pdf');
    }

    /**
     * Cierra la cuenta de la recepción: persiste los Charge, genera la ODV
     * en Microsip y marca la Account como CLOSED.
     */
    public function closeAccount(int $reception, AccountStatementService $statementService)
    {
        $receptionModel = Reception::with('episode.account')->findOrFail($reception);
        $this->authorize('update', $receptionModel);

        try {
            return response()->json($statementService->close($receptionModel));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function history(int $id)
    {
        $cremation = Cremation::with("reception", "pet", "serv", "service")->where('reception_id', $id)->first();
        $reception = Reception::with("payment")->find($id);

        return view('cremation.history', compact('reception', 'cremation'));
    }
}
