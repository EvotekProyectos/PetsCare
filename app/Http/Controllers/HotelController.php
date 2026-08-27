<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Http\Requests\HotelRequest;
use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Cubicle;
use App\Models\Folio;
use App\Models\Format;
use App\Models\GenericModel;
use App\Models\HotelStatus;
use App\Models\PaymentOrder;
use App\Models\Producto;
use App\Models\Reason;
use App\Models\Reception;
use App\Models\Video;
use App\Services\AccountStatementService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $hotelStatuses = HotelStatus::all();
        $this->authorize("viewAny", Hotel::class);
        return view('hotel.index', compact('hotels', 'hotelStatuses'))
            ->with('i', (request()->input('page', 1) - 1) * $hotels->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create($id)
    // {
    //     $this->authorize("create", Hotel::class);

    //     $hotel = new Hotel();
    //     $reception = Reception::with('pet', 'family', 'vet')->findorfail($id);
    //     $products = Producto::where("ESTATUS",  "A")->get();
    //     $cubicles = Cubicle::where("state", "0")->get();
    //     return view('hotel.create', compact('hotel', 'reception', 'products', 'cubicles'));
    // }

    public function create($id)
    {
        $this->authorize("create", Hotel::class);

        $hotel = new Hotel();

        $reception = Reception::with('pet', 'family', 'vet')->findorfail($id);
        $products = Producto::where("ESTATUS",  "A")->get();
        $cubicles = Cubicle::where("state", "0")->get();
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $reasons = Reason::all();


        return view('hotel.create', compact('hotel', 'reception', 'products', 'cubicles',
    'admissions', 'areas', 'reasons'));
    }


    //Crear Extension de dias 
    public function createExtension($id)
    {
        $this->authorize("create", Hotel::class);

        // Obtener la última recepción del tipo "hotel" (receptiontypeid = 4)
        $reception = Reception::with('pet', 'family', 'vet')->findorfail($id);

        // Buscar el hotel asociado a esa recepción (si existe)
        $lastHotel = null;
        if ($reception) {
            $lastHotel = Hotel::with('servicie', 'cubicle', 'serv')->where('reception_id', $reception->id)
                ->latest()
                ->first();
        }

        // Crear un nuevo objeto de Hotel sin guardar aún
        $hotel = new Hotel();

        // Obtener productos y cubículos disponibles
        $products = Producto::where("ESTATUS", "A")->get();
        //$cubicles = Cubicle::where("state", "0")->get();
        $cubicles = Cubicle::all();
        // Retornar la vista con los datos
        //return response()->json($lastHotel);
        return view('hotel.createExtension', compact('hotel', 'reception', 'lastHotel', 'products', 'cubicles'));
    }

    // public function listExtension($id){
    //     $hotels = Hotel::with('servicie', 'cubicle', 'serv')
    //                    ->where('reception_id', $id)
    //                    ->latest()
    //                    ->get();  // Usar get() en vez de first()

    //     // Formatear los datos para que DataTables los pueda usar
    //     $data = $hotels->map(function($hotel) {
    //         return [
    //             'id' => $hotel->id,
    //             'number_days' => $hotel->number_days,
    //             'servicie' => $hotel->servicie,
    //             'cubicle' => $hotel->cubicle,
    //             'serv' => $hotel->serv,
    //         ];
    //     });

    //     return response()->json($data);
    // }

    public function listExtension($id)
    {
        $hotels = Hotel::with('servicie', 'cubicle', 'serv')
            ->where('reception_id', $id)
            ->latest()
            ->take(1) // Solo obtener el último registro
            ->get();

        return response()->json($hotels);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(HotelRequest $request)
    {
        $this->authorize("create", Hotel::class);
        $validatedData = $request->validated();

        // Obtener la recepción asociada
        $reception = Reception::findOrFail($validatedData['reception_id']);
        $this->guardReceptionNotTransferred($reception);

        // Calcular el número de días entre la fecha de entrada y salida
        $entryDate = Carbon::parse($reception->entry_date);
        $exitDate = Carbon::parse($reception->exit_date);
        $days = ceil($entryDate->diffInHours($exitDate) / 24); // Convertir horas a días y redondear hacia arriba
        $days = ceil($days); // Redondear siempre hacia arriba

        if (isset($validatedData['cubicle_id'])) {
            // Puedes agregar el cubicle_id explícitamente si es necesario
            // Si ya está en $validatedData no es necesario, pero asegúrate de que no falte
            $validatedData['cubicle_id'] = $request->cubicle_id;  // Puede ser innecesario si ya está validado
        }

        // Cambiar el estado del cubículo elegido
        $cubicle = Cubicle::find($validatedData['cubicle_id']);
        if ($cubicle) {
            $cubicle->state = 1;
            $cubicle->start_date = $entryDate;
            $cubicle->end_date = $exitDate;
            $cubicle->save();
        }

        //Obtener folio consecutivo correspondiente y asignarlo en el form 
        $newFolio = $this->folio()->getData()->folio;
        $validatedData['folio'] = $newFolio;


        // Asignar el número de días calculado antes de crear el registro en la base de datos
        $validatedData['number_days'] = $days;
        // dd($validatedData);
        $hotel = Hotel::create($validatedData);
        return response()->json($hotel);
    }

    public function storeExtension(HotelRequest $request)
    {
        $this->authorize("create", Hotel::class);
        $validatedData = $request->validated();

        $this->guardReceptionNotTransferred(Reception::findOrFail($validatedData['reception_id']));

        // Obtener el cubículo
        $cubicle = Cubicle::findOrFail($validatedData['cubicle_id']);

        // Definir nuevas fechas: start_date será el último end_date del cubículo
        $startDate = Carbon::parse($cubicle->end_date);
        $endDate = $startDate->copy()->addDays($validatedData['number_days']);

        // Actualizar el cubículo con las nuevas fechas
        $cubicle->start_date = $startDate;
        $cubicle->end_date = $endDate;
        $cubicle->state = 1;
        $cubicle->save();

        // Crear la nueva extensión en la tabla `hotels`
        // $validatedData['start_date'] = $startDate;
        // $validatedData['end_date'] = $endDate;

        $validatedData['extension'] = 1;

        $hotel = Hotel::create($validatedData);


        //return response()->json($endDate);
        return response()->json([
            'message' => 'Extensión registrada exitosamente',
            'hotel' => $hotel
        ]);
    }


    public function example($id)
    {
        // Obtener la recepción asociada
        $reception = Reception::findOrFail($id);

        // Calcular los días entre entry_date y exit_date
        $entryDate = Carbon::parse($reception->entry_date);
        $exitDate = Carbon::parse($reception->exit_date);
        $days = $entryDate->diffInHours($exitDate) / 24; // Convertir horas a días
        $days = ceil($days); // Redondear siempre hacia arriba

        return response()->json($days);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //$this->authorize("view", Hotel::class);
        // $hotel = Hotel::find($id);
        $reception = Reception::with('pet', 'family')->findorfail($id);

        return view('hotel.show', compact('reception'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $hotel = Hotel::find($id);
        $this->authorize("update", $hotel);
        $reception = Reception::with('pet', 'family', 'vet')->findorfail($id);
        $products = Producto::where("ESTATUS",  "A")->get();
        $cubicles = Cubicle::where("state", "0")->get();
        return view('hotel.edit', compact('hotel', 'reception', 'products', 'cubicles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HotelRequest $request, Hotel $hotel)
    {
        $hotel->update($request->validated());
        $this->authorize("update", $hotel);
        return redirect()->route('hotels.index')
            ->with('success', 'Hotel updated successfully');
    }

    public function destroy($id)
    {
        $hotel = Hotel::find($id);
        // $this->authorize("delete", $hotel);
        $cubicleId = $hotel->cubicle_id;
        $hotel->delete();

        //regresar el estado del cubiculo a vacío
        $cubicle = Cubicle::find($cubicleId);
        if ($cubicle) {
            $cubicle->state = 0;
            $cubicle->save();
        }
        return response()->json($hotel);
    }

    //Función para registrar la salida de la mascota
    public function exitPet($id)
    {
        $hotel = Hotel::find($id);
        $cubicleId = $hotel->cubicle_id;

        //cambiar estado de la mascota
        $cubicle = Cubicle::find($cubicleId);
        if ($cubicle) {
            $cubicle->state = 0;
            $cubicle->save();
        }
        return response()->json($hotel);
    }

    //Registrar la fecha de salida de la mascota
    public function finishDate(Request $request)
    {
        $hotel = Hotel::find($request->hotel);
        $receptionId = $hotel->reception_id;

        // Obtener todos los registros de hotel con el mismo reception_id
        $hotels = Hotel::where('reception_id', $receptionId)->get();

        $totalDays = $hotels->sum('number_days'); // Sumar los días acordados
        $firstEntryDate = $hotels->min('entry_date'); // Obtener la primera fecha de entrada
        $daysElapsed = now()->diffInDays($firstEntryDate); // Días transcurridos desde la primera entrada

        if ($hotels->count() > 1) {
            // Si hay más de un registro de hotel, comparar con la suma de number_days
            if ($daysElapsed != $totalDays) {
                return response()->json(['message' => 'Los días transcurridos no coinciden con los días acordados'], 400);
            }
        } else {
            // Si solo hay un registro, comparar entry_date con exit_date
            if ($hotel->exit_date) {
                $expectedDays = $hotel->entry_date->diffInDays($hotel->exit_date);
                if ($daysElapsed != $expectedDays) {
                    return response()->json(['message' => 'Los días transcurridos no coinciden con la fecha de salida esperada'], 400);
                }
            }
        }
        // Cambiar el status del cubículo
        if ($hotel->cubicle_id) {
            $cubicle = Cubicle::find($hotel->cubicle_id);

            if ($cubicle) {
                $cubicle->state = 0; // Cambiar el status a 0
                $cubicle->save();
            } else {
                return response()->json(['message' => 'No se encontró el cubículo asociado al hotel'], 400);
            }
        } else {
            return response()->json(['message' => 'No se encontró cubículo asociado al hotel'], 400);
        }

        // Registrar la fecha de salida
        $hotel->finish_date = now();
        $hotel->save();

        return response()->json(['message' => 'Fecha de salida registrada con éxito']);
    }

    //Utilizada para mostrar los datos en la tabla del form de creacion
    public function list(int $id)
    {
        $hotels = Hotel::with('servicie', 'cubicle', 'serv')->where('reception_id', $id)->get();
        return DataTables::of($hotels)->make(true);
    }


    //Responsiva del servicio vista
    // public function hotelFormat(int $id)
    // {
    //     $hotels = Hotel::with(['reception', 'cubicle', 'servicie', 'serv'])->where('reception_id', $id)->get();
    //     $reception = $hotels->first()->reception;

    //     return view('hotel.responsiva', compact('hotels', 'reception'));
    // }

    public function hotelFormat(int $id)
    {
        $hotel = Hotel::with(['reception', 'cubicle', 'servicie', 'serv'])
            ->where('reception_id', $id)
            ->latest('id') // Obtener el último registro
            ->first();

        $reception = $hotel->reception;

        return view('hotel.responsiva', compact('hotel', 'reception'));
        //return response()->json($reception);
        //return view('hotel.responsiva', compact('hotel', 'reception'));
    }


    //Responsiva del servicio en pdf
    public function FormatPdf(Request $request, int $id)
    {
        $hotel = Hotel::with(['reception', 'cubicle', 'servicie', 'serv'])
            ->where('reception_id', $id)
            ->latest('id') // Obtener el último registro
            ->first();

        $reception = $hotel->reception;
        $signatureDataUrl = $request->input('signature');

        $pdf = PDF::loadView('hotel.responsiva', [
            'reception' => $reception,
            'hotel' => $hotel,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);

        $pdfPath = '/formats/pension_' . $id . '.pdf';
        Storage::put('public' . $pdfPath, $pdf->output());

        $format = new Format();
        $format->format_type_id = 5;
        $format->reception_id = $id;
        $format->pet_id = $reception->pet->id;
        $format->format_pdf = $pdfPath;
        $format->save();

        $pdfUrl = Storage::url($pdfPath);

        return response()->json(['url' => asset('storage' . $pdfPath)]);
    }

    /**
     * Resumen agregado de disponibilidad de cubículos por tipo de pensión —
     * widget informativo en el tab Hotel del Index de Recepciones
     */
    public function availabilitySummary()
    {
        $this->authorize('viewAny', Reception::class);

        $summary = Cubicle::selectRaw('cubicle_type_id, COUNT(*) as total, SUM(CASE WHEN state = 0 THEN 1 ELSE 0 END) as available')
            ->groupBy('cubicle_type_id')
            ->with('cubicleType:id,name')
            ->get();

        return response()->json($summary);
    }

    //Vista de todos los cubiculos con sus recepciones
    public function viewCubicles()
    {
        $cubicles = Cubicle::all();
        $cubicles = $cubicles->map(function ($cubicle) {
            $cubicle->hotels = Hotel::where('cubicle_id', $cubicle->id)->with('reception')->latest('created_at')->first();
            return $cubicle;
        });

        //return response()->json($cubicles);
        return view('cubicle.view', compact('cubicles'));
    }

    //Index del modulo
    public function totalIndex(Request $request)
    {
        $hotels = Hotel::with('reception', 'reception.pet', 'reception.family', 'cubicle', 'serv', 'servicie')
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereHas('reception', function ($q) use ($request) {
                    $q->whereDate('entry_date', $request->date);
                });
            })
            ->when($request->filled('status_id'), function ($query) use ($request) {
                $query->whereHas('reception.currentHotelStatus', function ($q) use ($request) {
                    $q->where('hotel_status_id', $request->status_id);
                });
            })
            ->get();

        // Verificamos la existencia del video para cada hotel
        $hotels = $hotels->map(function ($hotel) {
            // Asegurarnos de que el hotel tiene una recepción antes de acceder a reception_id
            $receptionId = optional($hotel->reception)->id;
            $videoExists = false;

            if ($receptionId) {
                $videoExists = Video::where('reception_id', $receptionId)
                    ->whereDate('send_date', Carbon::today())
                    ->exists();
            }

            // Agregar la propiedad al objeto
            $hotel->setAttribute('videoExists', $videoExists);
            return $hotel;
        });

        return response()->json($hotels);
    }


    //
    public function existExtension($reception)
    {
        $exists = Hotel::where('reception_id', $reception)
            ->where('extension', 1)
            ->exists();

        return response()->json($exists);
    }

    //Muestra todo los videos que pertenezcan a la recepcion
    public function existVideos($id)
    {
        $video = Video::with('user')->where('reception_id', $id)->get();
        return response()->json($video);
    }


    //Muestra si existe un video con fecha del día actual
    public function videoSent($id)
    {
        $reception = Reception::with('pet')->findorfail($id);
        $videoExists = Video::with('user')->where('reception_id', $id)->whereDate('send_date', Carbon::today())->exists();
        // Hotel::where('reception_id', $id)->update(['video' => $videoExists ? 1 : 0]);

        return response()->json([
            'reception' => $reception,
            'videoExists' => $videoExists,
        ]);
    }

    //Registra el envio del video
    public function sendVideo($id)
    {
        $video = new Video();
        $video->reception_id = $id;
        $video->send_date = Carbon::now();
        $video->status = 1;
        $video->user_id = Auth::user()->id;
        $video->save();

        // Actualizar el estado del video en la tabla Hotel
        Hotel::where('reception_id', $id)->update(['video' => 1]);

        return response()->json($video);
    }

    //Informacion detallada del servicio pdf
    public function serviceDetails(int $id)
    {
        $reception = Reception::find($id);
        $hotels = Hotel::where('reception_id', $id)->get();
        $pdf = Pdf::loadView("format.pension_datos", compact("reception", "hotels"));
        return $pdf->stream('pension.inf');
    }

    //Detalles en el historial de la mascota
    public function history(int $id)
    {
        $hotels = Hotel::with(['reception', 'cubicle', 'servicie', 'serv'])
            ->where('reception_id', $id)
            ->get();
        $reception = $hotels->first()->reception;

        return view('hotel.history', compact('hotels', 'reception'));
    }

    public function cubicle()
    {
        $hotels = Hotel::with(['reception', 'reception.pet', 'cubicle', 'servicie', 'serv'])
            ->whereNull('finish_date')
            ->get();


        return view('cubicle.viewTable', compact('hotels'));
    }

    //Lista de cubiculos ocupados
    public function unavailableCubicles()
    {
        $hotels = Hotel::with(['reception', 'reception.pet', 'cubicle', 'servicie', 'serv'])
            ->whereNull('finish_date')
            ->get();

        return response()->json($hotels);
    }

    //Generar los eventos en el calendario de disponibilidad cubiculos 
    public function getEvents()
    {
        $allEvents = Hotel::with('reception', 'cubicle', 'cubicle.cubicleType', 'servicie', 'serv')
            ->whereHas('cubicle', function ($query) {
                $query->where('state', 1);
            })->get();
        $events = [];

        foreach ($allEvents as $event) {

            $events[] = [

                'title' => ($event->serv->NOMBRE ?? 'Sin producto') . ' para ' . ($event->reception->pet->name ?? 'Sin mascota'),
                'pet' => ($event->reception->pet->name ?? 'Sin mascota') . ' Collar:' . ($event->reception->num ?? 'Sin collar'),
                //  'start' => $event->cubicle->start_date,
                //  'end' => $event->cubicle->end_date,
                'start' => $event->extension == 0 ? ($event->reception->entry_date ?? '') : ($event->cubicle->start_date ?? ''),
                'end' => $event->extension == 0 ? ($event->reception->exit_date ?? '') : ($event->cubicle->end_date ?? ''),

                'backgroundColor' => $event->cubicle->cubicleType->color,
                'borderColor' => $event->cubicle->cubicleType->color,
                'textColor' => '#000000', // Letra en negro

            ];
        }

        return response()->json($events);
    }

    //Obtener los cubiculos en base a su servicio de pension
    public function CubiclesByArticle($articleId)
    {
        // Mapeo de ARTICULO_ID a cubicle_type_id
        $articleToCubicleType = [
            136655 => 1,
            136659 => 2,
            136663 => 3,
            136667 => 4,
        ];

        // Verifica si el artículo tiene una relación con cubicle_type_id
        $cubicleTypeId = $articleToCubicleType[$articleId] ?? null;

        if (!$cubicleTypeId) {
            return response()->json(['message' => 'No hay cubículos para este artículo.'], 404);
        }
        // Obtiene los cubículos que coinciden con el cubicle_type_id y que esten disponibles
        $cubicles = Cubicle::where('cubicle_type_id', $cubicleTypeId)->where('state', 0)->get();
        return response()->json($cubicles);
    }


    //     //Generar orden de venta
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
        //     //Buscar Los servicios registrados a la recepción , son los id de productos en microsip
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
            //         //Calculo total
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

        //     //Inicilaizar los modelos para las tablas a las cuales les haremos insert en base al generico
        $basePVModel = new GenericModel('DOCTOS_PV', 'DOCTO_PV_ID');
        $detPVModel = new GenericModel('DOCTOS_PV_DET', 'DOCTO_PV_DET_ID');
        //Desactivamos las timestamps
        $basePVModel->timestamps = false;
        $detPVModel->timestamps = false;

        //     //Insert para la cabecera de DOCTOS_PV y guardamos el ID generado
        $ordenId = $basePVModel->createGeneric($ordenFields);

        //Inserts de cada partida para la tabla DOCTOS_PV_DET
        foreach ($listadoPartidas as $partida) {
            $partida['DOCTO_PV_ID'] = $ordenId;
            $detPVModel->createGeneric($partida);
        }

        //     //Guardamos en nuestra BD el Folio Generado para control de los pagos
        $data = [
            'reception_id' => $reception,
            'folio_odv' => $newFolio
        ];
        PaymentOrder::create($data);

        //Regresamos el Folio de la ODV con el que pueden pasar a pagar a caja
        return response()->json($newFolio);
    }

    /**
     * Previsualización del estado de cuenta de una recepción de hotel/pensión:
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


    // Generar orden de venta
    // public function ordenventa(int $reception)
    // {
    //     // Obtener folio actual y generar el nuevo folio
    //     $folio = Folio::where('CAJA_ID', 170159)->firstOrFail()->CONSECUTIVO;
    //     $newFolio = 'N' . str_pad($folio + 1, 8, '0', STR_PAD_LEFT);
    //     Folio::where('CAJA_ID', 170159)->increment('CONSECUTIVO', 1);

    //     // Variables para los detalles y totales
    //     $listadoPartidas = [];
    //     $importeNeto = 0;
    //     $now = Carbon::now();

    //     // Obtener el último registro del hotel asociado a la recepción
    //     $hotel = Hotel::where('reception_id', $reception)
    //         ->whereNotNull('service_type_id')
    //         ->latest('id') // Tomar solo el último registro
    //         ->first();


    //     // Si no tiene días registrados, asumir 1 día
    //     $dias = $hotel->number_days ?? 1;

    //     // Query para obtener detalles del servicio desde Microsip
    //     $articuloDetalle = DB::connection('firebird')
    //         ->table('ARTICULOS AS a')
    //         ->leftJoin('PRECIOS_ARTICULOS AS pa', 'a.ARTICULO_ID', '=', 'pa.ARTICULO_ID')
    //         ->leftJoin('CLAVES_ARTICULOS AS ca', 'a.ARTICULO_ID', '=', 'ca.ARTICULO_ID')
    //         ->where('a.ARTICULO_ID', $hotel->service_type_id)
    //         ->select('a.NOMBRE', 'a.ARTICULO_ID', 'pa.PRECIO', 'ca.CLAVE_ARTICULO')
    //         ->first();

    //     // Verificar si el servicio existe en Microsip
    //     if ($articuloDetalle) {
    //         $precioTotal = floatval($articuloDetalle->PRECIO) * $dias;

    //         $listadoPartidas[] = [
    //             'CLAVE_ARTICULO' => $articuloDetalle->CLAVE_ARTICULO,
    //             'ARTICULO_ID' => $articuloDetalle->ARTICULO_ID,
    //             'UNIDADES' => $dias, // Cantidad de días
    //             'UNIDADES_DEV' => 0,
    //             'TIPO_CONTAB_UNID' => 0,
    //             'PRECIO_UNITARIO' => $articuloDetalle->PRECIO,
    //             'PRECIO_UNITARIO_IMPTO' => $articuloDetalle->PRECIO,
    //             'IMPUESTO_POR_UNIDAD' => 0,
    //             'PCTJE_DSCTO' => 0,
    //             'PRECIO_TOTAL_NETO' => $precioTotal,
    //             'PRECIO_MODIFICADO' => 'N',
    //             'PCTJE_COMIS' => 0,
    //             'ROL' => 'N',
    //             'POSICION' => 1,
    //             'DSCTO_ART' => 0,
    //             'DSCTO_EXTRA' => 0,
    //         ];

    //         // Acumular total
    //         $importeNeto += $precioTotal;
    //     }

    //     // Insertar datos en DOCTOS_PV
    //     $ordenFields = [
    //         'CAJA_ID' => 170159,
    //         'TIPO_DOCTO' => 'O',
    //         'SUCURSAL_ID' => 54057,
    //         'FOLIO' => $newFolio,
    //         'FECHA' => $now->format('Y-m-d'),
    //         'HORA' => $now->format('H:i:s'),
    //         'CAJERO_ID' => 170160,
    //         'CLIENTE_ID' => 860,
    //         'ALMACEN_ID' => 953,
    //         'MONEDA_ID' => 1,
    //         'IMPUESTO_INCLUIDO' => 'S',
    //         'TIPO_CAMBIO' => 1,
    //         'TIPO_DSCTO' => 'P',
    //         'DSCTO_PCTJE' => 0,
    //         'DSCTO_IMPORTE' => 0,
    //         'ESTATUS' => 'N',
    //         'APLICADO' => 'S',
    //         'SISTEMA_ORIGEN' => 'PV',
    //     ];

    //     // Insert en DOCTOS_PV y obtener el ID generado
    //     $basePVModel = new GenericModel('DOCTOS_PV', 'DOCTO_PV_ID');
    //     $basePVModel->timestamps = false;
    //     $ordenId = $basePVModel->createGeneric($ordenFields);

    //     // Insertar detalles en DOCTOS_PV_DET
    //     $detPVModel = new GenericModel('DOCTOS_PV_DET', 'DOCTO_PV_DET_ID');
    //     $detPVModel->timestamps = false;

    //     foreach ($listadoPartidas as $partida) {
    //         $partida['DOCTO_PV_ID'] = $ordenId;
    //         $detPVModel->createGeneric($partida);
    //     }

    //     // Guardar en nuestra BD el Folio Generado
    //     PaymentOrder::create([
    //         'reception_id' => $reception,
    //         'folio_odv' => $newFolio,
    //     ]);

    //     // Devolver el folio de la ODV
    //     return response()->json($newFolio);
    // }

    public function Hotel(int $reception)
    {
        // Obtener el último registro del hotel asociado a la recepción
        $hotel = Hotel::where('reception_id', $reception)
            ->whereNotNull('service_type_id')
            ->latest('id') // Tomar solo el último registro
            ->first();

        return response()->json($hotel);
    }

    public function folio()
    {
        $lastRecord = Hotel::where('extension', 0)
            ->orderBy('folio', 'desc')
            ->first();

        $newFolio = $lastRecord ? (($lastRecord->folio % 200) + 1) : 1;
        return response()->json(['folio' => $newFolio]);
    }
}
