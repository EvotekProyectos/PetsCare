<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Http\Requests\HotelRequest;
use App\Models\Cubicle;
use App\Models\Folio;
use App\Models\GenericModel;
use App\Models\PaymentOrder;
use App\Models\Producto;
use App\Models\Reception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;

/**
 * Class HotelController
 * @package App\Http\Controllers
 */
class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotel::paginate();

        return view('hotel.index', compact('hotels'))
            ->with('i', (request()->input('page', 1) - 1) * $hotels->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $hotel = new Hotel();
        $reception = Reception::with('pet','family', 'vet')->findorfail($id);
        $products = Producto::where("ESTATUS",  "A")->get();
        $cubicles=Cubicle::all();
        return view('hotel.create', compact('hotel', 'reception', 'products', 'cubicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HotelRequest $request)
    {
        $new=Hotel::create($request->validated());

        return response()->json($new);
        // return redirect()->route('hotels.index')
        //     ->with('success', 'Hotel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hotel = Hotel::find($id);

        return view('hotel.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $hotel = Hotel::find($id);

        return view('hotel.edit', compact('hotel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HotelRequest $request, Hotel $hotel)
    {
        $hotel->update($request->validated());

        return redirect()->route('hotels.index')
            ->with('success', 'Hotel updated successfully');
    }

    public function destroy($id)
    {
        $hotel=Hotel::find($id);
        $hotel->delete();

        return response()->json($hotel);
        // return redirect()->route('hotels.index')
        //     ->with('success', 'Hotel deleted successfully');
    }

    
    public function list(int $id)
    {
        $hotels = Hotel::with('servicie', 'cubicle', 'serv')->where('reception_id', $id)->get();
        return DataTables::of($hotels)->make(true);
    }

    public function hotelFormat(int $id)
    {
        $hotels = Hotel::with(['reception', 'cubicle', 'servicie', 'serv'])
        ->where('reception_id', $id)
        ->get();
        $reception = $hotels->first()->reception;
        //$hotel=$hotels->first();

        return view('format.pensionPdf', compact('hotels', 'reception'));
        //return response()->json($hotels);

    }

    public function FormatPdf(Request $request, int $id)
    {
        dd($id);
        $reception = Reception::find($id);
        $hotel = Hotel::with('servicie', 'serv')->where('reception_id', $id)->get();
        $signatureDataUrl = $request->input('signature');

        $pdf = PDF::loadView('format.pensionPdf', [
            'reception' => $reception,
            'hotel' => $hotel,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);

        $pdfPath = '/formats/pension_' . $id . '.pdf';
        Storage::put('public' . $pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        return response()->json(['url' => asset('storage' . $pdfPath)]);
    }


    // public function view(){
    //     $hotel= Hotel::with(['reception','cubicle'])->get();
    //     $cubicles = Cubicle::all();

    //     return view ('cubicle.view' , compact('cubicles', 'hotel'));
    //     //return response()->json($hotel);
    // }

    public function view(){
        $cubicles = Cubicle::all();
        $hotel = Hotel::with('reception')->first(); // Suponiendo que solo haya un hotel
    
        return view('cubicle.view', compact('cubicles', 'hotel'));
    }
    

     public function all()
     {
         $hotels = Hotel::with('reception', 'reception.pet','reception.family','cubicle','serv','servicie')->get();
         //return DataTables::of($hotels)->make(true);
         return response()->json($hotels);
     }

//     public function all()
// {
//     $hotels = Hotel::with('reception', 'cubicle', 'serv', 'servicie')
//         ->select('reception_id', DB::raw('MIN(id) as id'))
//         ->groupBy('reception_id')
//         ->get();

//         return response()->json($hotels);
//     //return DataTables::of($hotels)->make(true);
// }


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
        $Ids = Hotel::where('reception_id', $reception)
            ->where(function ($query) {
                $query->whereNotNull('service_type_id');
            })
            ->pluck('service_type_id');

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


    public function pensionInf(int $id)
    {
        $reception = Reception::find($id);
        $hotels = Hotel::where('reception_id', $id)->get();
        $pdf = Pdf::loadView("format.pension_datos", compact( "reception", "hotels"));
        return $pdf->stream('pension.inf');

        //return response()->json($hotels);
        //return view('format.pension_datos', compact('reception'));
    }
 }
