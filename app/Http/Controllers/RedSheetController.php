<?php

namespace App\Http\Controllers;

use App\Models\RedSheet;
use App\Http\Requests\RedSheetRequest;
use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Cremation;
use App\Models\Folio;
use App\Models\FollowUp;
use App\Models\FollowupSurgical;
use App\Models\GenericModel;
use App\Models\Producto;
use App\Models\HospitalDischarge;
use App\Models\Hospitalization;
use App\Models\ReceptionEvent;
use App\Models\VoucherProduct;
use App\Models\Log;
use App\Models\PaymentOrder;
use App\Models\Prescription;
use App\Models\ProductType;
use App\Models\Reason;
use App\Models\Reception;
use App\Models\Surgery;
use App\Services\AccountStatementService;
use App\Services\VoucherService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class RedSheetController
 * @package App\Http\Controllers
 */
class RedSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $redSheets = RedSheet::paginate();
        $this->authorize("viewAny", RedSheet::class);

        return view('red-sheet.index', compact('redSheets'))
            ->with('i', (request()->input('page', 1) - 1) * $redSheets->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $redSheet = new RedSheet();
        $this->authorize("create", RedSheet::class);
        return view('red-sheet.create', compact('redSheet'));
    }

    /**
     * se crea UN RedSheet por cada valor seleccionado en cada categoría, cada
     * uno con esa sola categoría poblada y las otras dos en null, igual que ya
     * funcionaba para un registro individual.
     */
    public function store(RedSheetRequest $request)
    {
        $this->authorize("create", RedSheet::class);
        $this->guardReceptionNotTransferred(Reception::findOrFail($request->reception_id));

        $common = [
            'reception_id' => $request->reception_id,
            'day_count' => $request->day_count,
            'vet_id' => $request->vet_id,
        ];

        $categories = [
            'service_type_id' => $request->input('service_type_id', []),
            'lab_type_id' => $request->input('lab_type_id', []),
            'imaging_type_id' => $request->input('imaging_type_id', []),
        ];

        $created = DB::transaction(function () use ($common, $categories) {
            $records = [];

            foreach ($categories as $field => $values) {
                foreach (array_filter($values) as $value) {
                    $records[] = RedSheet::create($common + [$field => $value]);
                }
            }

            return $records;
        });

        return response()->json($created);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("view", $redSheet);

        return view('red-sheet.show', compact('redSheet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("update", $redSheet);

        return view('red-sheet.edit', compact('redSheet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RedSheetRequest $request, RedSheet $redSheet)
    {
        $redSheet->update($request->validated());
        $this->authorize("update", $redSheet);

        return redirect()->route('red-sheets.index')
            ->with('success', 'RedSheet updated successfully');
    }

    public function destroy($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("delete", $redSheet);
        $redSheet->delete();

        return response()->json($redSheet);
    }

    /**
     * Elimina un servicio de la pantalla de hospitalización no
     * borra el registro: lo marca como eliminado 
     * Reglas:
     * solo el día actual (day_count más alto de la recepción), y un
     * consumible (ES_ALMACENABLE "S") con vale Surtido no se puede eliminar.
     */
    public function removeService(Request $request, $id, VoucherService $voucherService)
    {
        $redSheet = RedSheet::with('lab', 'imaging', 'serv', 'laboratory', 'img')->findOrFail($id);
        $this->authorize("delete", $redSheet);

        $request->validate(['reason' => 'required|string|max:1000']);

        $maxDayCount = RedSheet::where('reception_id', $redSheet->reception_id)->max('day_count');
        if ((int) $redSheet->day_count !== (int) $maxDayCount) {
            return response()->json([
                'message' => 'Solo se puede eliminar un servicio del día actual.'
            ], 422);
        }

        $producto = $redSheet->lab_type_id
            ? $redSheet->laboratory
            : ($redSheet->imaging_type_id ? $redSheet->img : $redSheet->serv);
        $esAlmacenable = $producto->ES_ALMACENABLE ?? null;

        if ($esAlmacenable === 'S') {
            $activeVoucherProduct = VoucherProduct::where('sourceable_id', $redSheet->id)
                ->where('sourceable_type', RedSheet::class)
                ->whereHas('voucher', fn ($q) => $q->whereNotIn('status', ['Cancelado', 'Rechazado']))
                ->with('voucher')
                ->first();

            if ($activeVoucherProduct && $activeVoucherProduct->voucher->status === 'Surtido') {
                return response()->json([
                    'message' => 'Este servicio ya tiene un vale Surtido y no puede eliminarse.'
                ], 422);
            }

            if ($activeVoucherProduct) {
                $voucherService->removeVoucherProductAndKeepConsistent($activeVoucherProduct, 'Eliminación de servicio');
            }
        }

        $redSheet->update([
            'removed_at' => now(),
            'removed_by' => auth()->id(),
            'removal_reason' => $request->reason,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Muestra la vista general de una mascota hospitalizada y le agrega servicios
     * */
    public function entry($id)
    {
        //Instanceamos el nuevo registros y todos los catalogos de la pantalla
        $redSheet = new RedSheet();
        $reception = Reception::with('pet', 'admissionType', 'area')->findorfail($id);
        $products = Producto::where("ESTATUS",  "A")->get();
        $followUp = new FollowUp();
        $followupSurgical = new FollowupSurgical();
        $surgery = new Surgery();
        $admissions = AdmissionType::all();
        $areas= Area::all();
        $reasons= Reason::all();
        $discharges = HospitalDischarge::all();
        $this->authorize("create", RedSheet::class); //verificamos los permisos
        return view('red-sheet.create', compact('redSheet', 'discharges', 'reception', 'products', 'followUp', 'followupSurgical', 'surgery', 'admissions', 'areas', 'reasons')); //regresdamos la vista con al info
    }

    /**
     * Versión de solo lectura de entry() — revisar una hospitalización ya
     * registrada sin poder capturar nada
     */
    public function showReception(int $id)
    {
        $this->authorize('viewAny', RedSheet::class);
        $reception = Reception::with('pet', 'admissionType', 'area')->findOrFail($id);

        return view('red-sheet.show-reception', compact('reception'));
    }


    /**
     * Recupera y combina datos de cirugías y hojas rojas (RedSheets) asociadas a una recepción.
     * */
    public function recap(int $id)
    {
        // Obtener las hojas rojas y cirugias asociadas a la recepción, incluyendo relaciones con otros modelos.
        $redsheets = RedSheet::with('vet', 'imaging', 'img', 'lab', 'laboratory', 'service', 'serv', 'removedByUser')->where('reception_id', $id)->get();
        $surgeries = Surgery::with('surgery', 'vet', 'surg',)->where('reception_id', $id)->get();

        $redsheetIds = $redsheets->pluck('id')->all();
        $activeVouchers = VoucherProduct::activeMapFor(RedSheet::class, $redsheetIds);
        $voucherHistory = VoucherProduct::historyMapFor(RedSheet::class, $redsheetIds);
        $redsheets = $redsheets->map(function ($redsheet) use ($activeVouchers, $voucherHistory) {
            $active = $activeVouchers->get($redsheet->id);
            $redsheet->setAttribute('active_voucher_folio', $active?->voucher?->folio);
            $redsheet->setAttribute('active_voucher_id', $active?->voucher_id);
            $redsheet->setAttribute('active_voucher_status', $active?->voucher?->status);

            $hasCancelledHistory = ($voucherHistory->get($redsheet->id) ?? collect())
                ->contains(fn ($vp) => in_array($vp->voucher?->status, ['Cancelado', 'Rechazado']));
            $redsheet->setAttribute('has_cancelled_history', $hasCancelledHistory);

            return $redsheet;
        });

        // Mapear los datos de las cirugías y relacionarlas con hojas rojas del mismo día.
        $combinedData = $surgeries->map(function ($surgery) use ($redsheets) {
            // Extraer la fecha de la cirugía en formato 'Y-m-d'
            $surgeryDate = \Carbon\Carbon::parse($surgery->created_at)->format('Y-m-d');

            $date_count = 0; // Inicializar el contador de días

            // Buscar una hoja roja que coincida con la fecha de la cirugía
            $matchingRedSheet = $redsheets->first(function ($redsheet) use ($surgeryDate) {
                $redsheetDate = \Carbon\Carbon::parse($redsheet->created_at)->format('Y-m-d');
                return $redsheetDate == $surgeryDate;
            });

            // Si existe una hoja roja en la misma fecha, asignar su contador de días a la cirugía
            if ($matchingRedSheet) {
                $date_count = $matchingRedSheet->day_count;
            }
            // Agregar el atributo 'day_count' a la cirugía
            $surgery->setAttribute('day_count', $date_count);

            return $surgery;
        });

        // Preparar la respuesta con cirugías y hojas rojas combinadas
        $allData = [
            'surgeries' => $combinedData,
            'redsheets' => $redsheets,
        ];
        // Devolver los datos en formato compatible con DataTables
        return DataTables::of($allData)->make(true);
    }

    /**
     * Bitácora de eventos (reception_events) de una recepción
     */
    public function events(int $id)
    {
        $reception = Reception::findOrFail($id);
        $entryDate = Carbon::parse($reception->entry_date)->startOfDay();

        $events = ReceptionEvent::with('createdBy')
            ->where('reception_id', $id)
            ->orderBy('created_at')
            ->get()
            ->map(function ($event) use ($entryDate) {
                $eventDate = Carbon::parse($event->created_at)->startOfDay();
                $dayCount = $entryDate->diffInDays($eventDate) + 1;

                return [
                    'day_count' => $dayCount,
                    'event_type' => $event->event_type,
                    'description' => $event->description,
                    'followup_type' => $event->followup_type,
                    'followup_id' => $event->followup_id,
                    'created_at' => $event->created_at,
                    'created_by' => $event->createdBy->name ?? 'Desconocido',
                ];
            });

        return response()->json($events);
    }

    // public function discharge(Request $request)
    // {
    //     $reception = Reception::findOrFail($request->receptionId);
    //     $reception->exit_date = now();
    //     $reception->save();

    //     $hospitalization = new Hospitalization();
    //     $hospitalization->reception_id = $request->receptionId;
    //     $hospitalization->exit_date = now();
    //     $hospitalization->hospital_discharges_id = 1;
    //     $hospitalization->save();

    //     return response()->json([
    //         'message' => 'Paciente dado de alta.',
    //     ], 200);
    // }

    // public function dischargePatient(Request $request)
    // {
    //     $request->validate([
    //         'receptionId' => 'required|exists:hospitalizations,reception_id',
    //         'dischargeType' => 'required|string'
    //     ]);

    //     $discharge = HospitalDischarge::where('name', $request->dischargeType)->first();

    //     if (!$discharge) {
    //         return response()->json(['message' => 'Tipo de alta no válido.'], 400);
    //     }

    //     $hospitalization = Hospitalization::where('reception_id', $request->receptionId)->first();

    //     if (!$hospitalization) {
    //         return response()->json(['message' => 'Hospitalización no encontrada.'], 404);
    //     }

    //     $hospitalization->update([
    //         'hospital_discharges_id' => $discharge->id,
    //         'exit_date' => now() // Asegura que se registre la fecha de salida actual
    //     ]);

    //     return response()->json(['message' => 'Alta registrada exitosamente.']);
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
        $redSheetIds = RedSheet::where('reception_id', $reception)
            ->where(function ($query) {
                $query->whereNotNull('service_type_id')
                    ->orWhereNotNull('imaging_type_id')
                    ->orWhereNotNull('lab_type_id');
            })
            ->get(['service_type_id', 'imaging_type_id', 'lab_type_id'])
            ->flatMap(function ($item) {
                return array_filter([$item->service_type_id, $item->imaging_type_id, $item->lab_type_id]);
            });

        $surgeryIds = Surgery::where('reception_id', $reception)
            ->where(function ($query) {
                $query->whereNotNull('product_type_id');
            })
            ->pluck('product_type_id');

        $cremationIds = Cremation::where('reception_id', $reception)
            ->where(function ($query) {
                $query->whereNotNull('servicie');
            })
            ->pluck('servicie');

        //Juntar todos los servicios registrados para cobrarlos 
        $articulos = $redSheetIds->merge($surgeryIds)->merge($cremationIds)->values()->all();

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

    /**
     * Previsualización del estado de cuenta de una recepción de hospitalización:
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
}
