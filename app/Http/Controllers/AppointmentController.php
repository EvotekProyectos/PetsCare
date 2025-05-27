<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\AppointmentRequest;
use App\Models\AppointmentService;
use App\Models\ControlDate;
use App\Models\Folio;
use App\Models\GenericModel;
use App\Models\PaymentOrder;
use App\Models\Prescription;
use App\Models\Producto;
use App\Models\ProductType;
use App\Models\Reason;
use App\Models\Reception;
use App\Models\ReceptionStatusHistory;
use App\Models\VaccineCertificate;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

/**
 * Class AppointmentController
 * @package App\Http\Controllers
 */
class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::paginate();
        $this->authorize("viewAny", Appointment::class);

        return view('appointment.index', compact('appointments'))
            ->with('i', (request()->input('page', 1) - 1) * $appointments->perPage());
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $appointment = new Appointment();
        $reasons = Reason::all();
        $prescription = new Prescription();

        $this->authorize("create", Appointment::class);
        return view('appointment.create', compact('appointment', 'reasons', 'prescription',));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $this->authorize("create", Appointment::class);

        $new  = Appointment::create($request->validated());
        ReceptionStatusHistory::create([
            'reception_id' => $request->reception_id,
            'attention_status_id' => 1,
        ]);

        $reception = Reception::find($request->reception_id);
        $day_next_check = $request->day_next_check;
        $time_next_check = $request->time_next_check;

        // Si no se define time_next_check, se asigna las 8:00 am
        if (empty($time_next_check)) {
            $time_next_check = '08:00:00';
        }
        // Concatenar la fecha y la hora para lograr el formato de tipo datetime
        $datetime = $day_next_check . ' ' . $time_next_check;

        ControlDate::createIfNotDuplicate([
            'reception_id' => $request->reception_id,
            'pet_id' => $reception ? $reception->pet_id : null,
            'family_id' => $reception ? $reception->family_id : null,
            'date_type_id' => $request->reason_next_check_id,
            'status_date_id' => 1,
            'user_id' => auth()->id(),
            'date' => $datetime,
        ]);

        return response()->json($new);

        // return redirect()->route('assignment.index')
        //     ->with('success', 'Consulta Finalizada Exitosamente, puedes seguir atendiendo al siguiente paciente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);
        $this->authorize("view", Appointment::class);

        return view('appointment.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointment = Appointment::find($id);
        $reasons = Reason::all();
        $this->authorize("update", $appointment);

        return view('appointment.edit', compact('appointment', 'reasons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());
        $this->authorize("update", $appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully');
    }

    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        $this->authorize("update", $appointment);
        $appointment->delete();

        return response()->json($appointment);
    }


    public function list($id)
    {
        $appointments = Appointment::with('reception', 'reason')->where('reception_id', $id)->get();
        return view('appointment.index', compact('appointments'));
    }


    public function consultation(int $id)
    {
        $appointment = new Appointment();
        $reception = Reception::with('pet', 'reason')->findorfail($id);
        $reasons = Reason::all();
        $prescription = new Prescription();
        $vaccineCertificate = new VaccineCertificate();
        $appointmentService = new AppointmentService();
        $products = Producto::where("ESTATUS",  "A")->get();
        $this->authorize("create", Appointment::class);
        // ReceptionStatusHistory::create([
        //     'reception_id' => $id,
        //     'attention_status_id' => 3,
        // ]);
        return view('appointment.create', compact('appointment', 'reasons', 'prescription', 'reception', 'vaccineCertificate', 'products', 'appointmentService'));
    }

    public function historic(int $id)
    {
        $reception = Reception::find($id);
        $appointment = Appointment::where("reception_id", $id)->get()->first();
        $prescription = Prescription::where("reception_id", $id)->get()->first();

        return view('appointment.historic', compact('appointment', 'prescription', 'reception'));
    }

    public function ordenventa(int $reception, int $concepto)
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
        $servicesIds = AppointmentService::where('reception_id', $reception)
            ->where(function ($query) {
                $query->whereNotNull('imaging_type_id')
                    ->orWhereNotNull('lab_type_id');
            })
            ->get(['imaging_type_id', 'lab_type_id'])
            ->flatMap(function ($item) {
                return array_filter([$item->imaging_type_id, $item->lab_type_id]);
            });

        $vaccinesIds = VaccineCertificate::where('reception_id', $reception)
            ->where(function ($query) {
                $query->whereNotNull('product');
            })
            ->pluck('product');

        //Juntar todos los servicios registrados para cobrarlos 
        $articulos = $servicesIds->merge($vaccinesIds)->merge($concepto)->values()->all();

        //Recorrer Cada Servicio para sacar los detalleS que guardamos de la ODV y calculamos total
        foreach ($articulos as $articuloId) {
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
}
