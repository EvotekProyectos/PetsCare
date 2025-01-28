<?php

namespace App\Http\Controllers;

use App\Models\Grooming;
use App\Http\Requests\GroomingRequest;
use App\Models\Folio;
use App\Models\Format;
use App\Models\GenericModel;
use App\Models\GroomingStatusHistory;
use App\Models\PaymentOrder;
use App\Models\Producto;
use App\Models\Reception;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class GroomingController
 * @package App\Http\Controllers
 */
class GroomingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groomings = Grooming::paginate();
        $this->authorize("viewAny", Grooming::class);

        return view('grooming.index', compact('groomings'))
            ->with('i', (request()->input('page', 1) - 1) * $groomings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grooming = new Grooming();
        $products = Producto::where("ESTATUS",  "A")->get();

        $this->authorize("create", Grooming::class);
        return view('grooming.create', compact('grooming', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroomingRequest $request)
    {
        $this->authorize("create", Grooming::class);
        $new = Grooming::create($request->validated());

        return response()->json($new);

        // return redirect()->route('groomings.index')
        //     ->with('success', 'Grooming created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $grooming = Grooming::find($id);
        $reception = Reception::with('pet', 'admissionType', 'area', 'statusGrooming.groomingStatus')->findorfail($id);
        // $this->authorize("view",$grooming);

        return view('grooming.show', compact('reception'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $grooming = Grooming::find($id);
        $this->authorize("update", $grooming);

        return view('grooming.edit', compact('grooming'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroomingRequest $request, Grooming $grooming)
    {
        $this->authorize("update", $grooming);
        $grooming->update($request->validated());

        return redirect()->route('groomings.index')
            ->with('success', 'Grooming updated successfully');
    }

    public function destroy($id)
    {
        $grooming = Grooming::find($id);
        $this->authorize("delete", $grooming);
        $grooming->delete();

        return response()->json($grooming);
    }

    public function list(int $id)
    {

        $groomings = Grooming::with('service', 'serv')->where('reception_id', $id)->get();

        return DataTables::of($groomings)->make(true);
    }

    public function status(Request $request)
    {
        $validatedData = $request->validate([
            'reception_id' => 'required|exists:receptions,id',
            'grooming_status_id' => 'required|exists:grooming_statuses,id',
        ]);

        try {
            $new = GroomingStatusHistory::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $new,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function groomingsign(int $id)
    {
        $reception = Reception::find($id);
        $groomings = Grooming::with('service', 'serv')->where('reception_id', $id)->get();

        return view('grooming.pdf', compact("reception", "groomings"));
    }

    public function groomingpdf(Request $request, int $id)
    {
        $reception = Reception::find($id);
        $groomings = Grooming::with('service', 'serv')->where('reception_id', $id)->get();
        $signatureDataUrl = $request->input('signature');

        $pdf = PDF::loadView('grooming.pdf', [
            'reception' => $reception,
            'groomings' => $groomings,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);

        $pdfPath = '/groomings/grooming_' . $id . '.pdf';
        Storage::put('public' . $pdfPath, $pdf->output());

        $format = new Format();
        $format->format_type_id = 4;
        $format->reception_id = $id;
        $format->pet_id = $reception->pet->id;
        $format->format_pdf = $pdfPath;
        $format->save();

        $pdfUrl = Storage::url($pdfPath);

        return response()->json(['url' => asset('storage' . $pdfPath)]);
    }

    public function generatePdf(int $id)
    {
        $reception = Reception::with('payment')->find($id);

        $groomings = Grooming::with('service', 'serv')->where('reception_id', $id)->get();

        $pdf = Pdf::loadView("grooming.pdf", compact("reception", "groomings"));

        return $pdf->stream("PDF.pdf");
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
        $articulosDetalles = [];
        $listadoPartidas = [];
        $importeNeto = 0;
        $now = Carbon::now();

        //Buscar Los servicios registrados a la recepción , son los id de productos en microsip
        $Ids = Grooming::where('reception_id', $reception)
            ->where(function ($query) {
                $query->whereNotNull('service_id');
            })
            ->pluck('service_id');

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

    public function history($id)
    {
        $reception = Reception::with('pet', 'admissionType', 'area', 'statusGrooming.groomingStatus')->findorfail($id);
        return view('grooming.history', compact('reception'));
    }
}
