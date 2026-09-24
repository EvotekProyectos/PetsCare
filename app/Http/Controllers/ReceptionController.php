<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use App\Http\Requests\ReceptionRequest;
use App\Models\Account;
use App\Models\Appointment;
use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\AttentionStatus;
use App\Models\CremationStatus;
use App\Models\CremationStatusHistory;
use App\Models\Episode;
use App\Models\FamClassification;
use App\Models\Family;
use App\Models\Format;
use App\Models\FormatType;
use App\Models\GeneralGrooming;
use App\Models\Genre;
use App\Models\Grooming;
use App\Models\GroomingStatus;
use App\Models\GroomingStatusHistory;
use App\Models\HospitalizationStatus;
use App\Models\HospitalizationStatusHistory;
use App\Models\HotelStatus;
use App\Models\HotelStatusHistory;
use App\Models\Pet;
use App\Models\PetClassification;
use App\Models\Prescription;
use App\Models\Producto;
use App\Models\Reason;
use App\Models\ReceptionEvent;
use App\Models\ReceptionStatusHistory;
use App\Models\ReceptionTransfer;
use App\Models\RedSheet;
use App\Models\ReproductiveStatus;
use App\Models\Room;
use App\Models\Species;
use App\Models\Surgery;
use App\Models\SystemVersion;
use App\Models\User;
use App\Services\AccountStatementService;
use App\Services\ReceptionDocumentService;
use App\Services\ReceptionVersionService;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

/**
 * Class ReceptionController
 * @package App\Http\Controllers
 */
class ReceptionController extends Controller
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
        $this->authorize("viewAny", Reception::class); //valida permiso de ver recepciones

        //catalogos necesarios para el partial del formulario que se incluye en el modal
        $reception = new Reception();
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::where("deceased", 0)->get();
        $veterinarians = User::role('medico')->get();      // rol id 3
        $collaborators = User::role('colaborador')->get(); // rol id 4

        //catalogos necesarios para el modal rápido de nueva mascota/familia
        $family = new Family();
        $genders = Genre::all();
        $ReproductiveStatuses = ReproductiveStatus::all();
        $PetClassifications = PetClassification::all();
        $FamClassifications = FamClassification::all();
        $Species = Species::where('active', true)->orderBy('name')->get();

        //catalogos de estatus para los filtros de las 5 tablas de recepciones
        $attentionStatuses = AttentionStatus::all();
        $hospitalizationStatuses = HospitalizationStatus::all();
        $groomingStatuses = GroomingStatus::all();
        $hotelStatuses = HotelStatus::all();
        $cremationStatuses = CremationStatus::all();

        // Usado por la columna Acciones de la tabla de Consultas
        $enEsperaAttentionStatusId = AttentionStatus::where('name', 'En espera')->value('id');

        return view('reception.index', compact(
            'reception',
            'admissions',
            'areas',
            'families',
            'reasons',
            'users',
            'rooms',
            'pets',
            'family',
            'genders',
            'ReproductiveStatuses',
            'PetClassifications',
            'FamClassifications',
            'Species',
            'veterinarians',
            'collaborators',
            'attentionStatuses',
            'hospitalizationStatuses',
            'groomingStatuses',
            'hotelStatuses',
            'cremationStatuses',
            'enEsperaAttentionStatusId'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //recopila los catalogos necesarios par que el form funcione
        $reception = new Reception();
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::where("deceased", 0)->get(); //solo trae mascotas que no estn marcadas como fallecidas
        $veterinarians = User::role('medico')->get();      // rol id 3
        $collaborators = User::role('colaborador')->get(); // rol id 4

        $this->authorize("create", Reception::class); //valida el permiso para crear recepciones
        return view('reception.create', compact(
            'reception',
            'admissions',
            'areas',
            'families',
            'reasons',
            'users',
            'rooms',
            'pets',
            'veterinarians',
            'collaborators'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReceptionRequest $request)
    {
        $this->authorize("create", Reception::class); //valida permiso de crear recepciones
        $reception = Reception::create($request->validated());

        // Cada recepción nace con su propio Episode y Account
        $episode = Episode::create([
            'pet_id' => $reception->pet_id,
            'status' => Episode::STATUS_OPEN,
            'opened_at' => now(),
        ]);

        Account::create([
            'episode_id' => $episode->id,
            'status' => Account::STATUS_OPEN,
        ]);

        $reception->update(['episode_id' => $episode->id]);

        $wantsJson = $request->ajax() || $request->wantsJson();

        if ($request->reception_type_id == 1) {
            ReceptionStatusHistory::create([
                'reception_id' => $reception->id,
                'attention_status_id' => AttentionStatus::where('name', 'En espera')->value('id'),
            ]);
        }

        if ($request->reception_type_id == 2) {
            HospitalizationStatusHistory::create([
                'reception_id' => $reception->id,
                'hospitalization_status_id' => HospitalizationStatus::where('name', 'Hospitalizado')->value('id'),
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]);
            // from=reception: esta recepción se creó directamente desde
            // Recepción (reception/form.blade.php, sin pasar por un
            // traslado), así que al firmar la responsiva debe volver aquí y
            // no a Hospitalizaciones (ver hospital_authorization()/
            // hospital_auth.js).
            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'reception_type_id' => 2,
                    'redirect' => route('hospital.list', ['id' => $reception->id, 'from' => 'reception']),
                ]);
            }
            return redirect()->route('hospital.list', ['id' => $reception->id, 'from' => 'reception'])
                ->with('success', 'Recepción de hospitalización guardada exitosamente.');
        } elseif ($request->reception_type_id == 5) {
            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'reception_type_id' => 5,
                    'redirect' => route('new.cremation', ['id' => $reception->id]),
                ]);
            }
            return redirect()->route('new.cremation', ['id' => $reception->id])
                ->with('success', 'Recepción de cremación guardada exitosamente.');
        }

        if ($request->reception_type_id == 3) { //en los casos de recepcion de grooming hace un registro de historial de atencion en esa tabla
            GroomingStatusHistory::create([
                'reception_id' => $reception->id,
                'grooming_status_id' => GroomingStatus::where('name', 'Creado')->value('id'),
            ]);
            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'reception_type_id' => 3,
                    'redirect' => route('receptions.grooming', $reception->id),
                ]);
            }
            return redirect()->route('receptions.grooming',  $reception->id); //redirigue a seguir llenado el form necesario para el grooming
        }


        if ($request->reception_type_id == 4) {
            HotelStatusHistory::create([
                'reception_id' => $reception->id,
                'hotel_status_id' => HotelStatus::where('name', 'En estancia')->value('id'),
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]);
            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'reception_type_id' => 4,
                    'redirect' => route('hotel.create', ['id' => $reception->id]),
                ]);
            }
            return redirect()->route('hotel.create', ['id' => $reception->id]);
        }

        if ($wantsJson) {
            return response()->json([
                'success' => true,
                'reception_type_id' => (int) $request->reception_type_id,
                'message' => 'Recepción guardada exitosamente.',
            ]);
        }

        return redirect()->route('receptions.index')
            ->with('success', 'Recepción guardada exitosamente.'); //el caso generico redigirue al index general de las recepciones
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reception = Reception::find($id);
        $this->authorize("view", Reception::class);
        return view('reception.show', compact('reception'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $reception = Reception::find($id);
        $this->authorize("update", $reception); //valida el permiso de edicion

        if ($request->ajax() || $request->wantsJson()) {
            //datos planos de la recepción para poblar el modal (los catalogos ya estan en el DOM del index)
            return response()->json([
                'id' => $reception->id,
                'reception_type_id' => $reception->reception_type_id,
                'entry_date' => $reception->entry_date ? date('Y-m-d\TH:i', strtotime($reception->entry_date)) : null,
                'exit_date' => $reception->exit_date ? date('Y-m-d\TH:i', strtotime($reception->exit_date)) : null,
                'family_id' => $reception->family_id,
                'pet_id' => $reception->pet_id,
                'admission_type_id' => $reception->admission_type_id,
                'area_id' => $reception->area_id,
                'reason_id' => $reception->reason_id,
                'veterinarian_id' => $reception->veterinarian_id,
                'room_id' => $reception->room_id,
                'num' => $reception->num,
            ]);
        }

        //recopila los catalogos para el form de pagina completa
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $veterinarians = User::role('medico')->get();      // rol id 3
        $collaborators = User::role('colaborador')->get(); // rol id 4
        $pets = Pet::where("deceased", 0)->get(); //filtra a mascotas no fallecidas

        return view('reception.edit', compact(
            'reception',
            'admissions',
            'areas',
            'families',
            'reasons',
            'users',
            'rooms',
            'pets',
            'veterinarians',
            'collaborators'
        )); //redirigue a la pantalla junto a todos los catalogos
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReceptionRequest $request, Reception $reception)
    {
        $this->authorize("update", $reception); //valida el permiso de editar
        $reception->update($request->validated()); //valida los datos y actualiza el registro

        if ((int) $reception->reception_type_id === 1) {

            $reception->load('reason');
            Appointment::where('reception_id', $reception->id)
                ->update(['service_id' => optional($reception->reason)->articulo_id]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'reception_type_id' => (int) $reception->reception_type_id,
                'message' => 'Recepción actualizada exitosamente.',
            ]);
        }

        return redirect()->route('receptions.index')
            ->with('success', 'Recepción actualizada exitósamente.'); //redirge al index
    }

    public function destroy($id)
    {
        $reception = Reception::find($id); //ecuentra que existe el registro a eliminar
        $this->authorize("delete", $reception); //valida el permiso para eliminar
        $reception->delete(); //elimina el registro

        return response()->json($reception); //regresa el mensje
    }

    /**
     * Usado por el polling del index para saber si hay que recargar la
     * tabla del tab activo (ver pollForChanges/startPollingReceptions en
     * receptions/index.js). Antes hacía 5x MAX(updated_at) (Reception +
     * las 4 status-history) sin índice, en cada poll de 30s de cada pestaña
     * abierta. Ahora es una sola lectura de system_versions -la versión la
     * incrementan los Observers de Reception/*StatusHistory/Account/
     * AdvancePayment (ver ReceptionVersionService::touch()), no este método-.
     *
     * Se asume que la fila 'receptions' de system_versions siempre existe:
     * la migración create_system_versions_table la siembra al correr, así
     * que no depende de que alguien haya visitado Recepciones antes. Este
     * endpoint es de solo lectura a propósito -no hace firstOrCreate()- para
     * no convertir un polling de lectura en una escritura condicional en
     * cada ciclo; si la fila llegara a faltar por alguna razón externa a
     * este flujo, value() devuelve null y el `?? 0` de abajo evita romper el
     * contrato JSON (el frontend solo compara igualdad, nunca asume que sea
     * un timestamp).
     */
    public function lastUpdateGlobal()
    {
        $version = SystemVersion::where('key', ReceptionVersionService::KEY)->value('version') ?? 0;

        return response()->json([
            'last_update' => $version,
        ]);
    }

    public function list(int $reception_type_id, Request $request)
    {
        if ($request->filled('date') && $request->filled('date_to') && $request->date > $request->date_to) {
            $request->merge(['date' => $request->date_to, 'date_to' => $request->date]);
        }

        //Recopilamos los registros de recepcion junto a todas las relaciones necesarias
        $receptions = Reception::with(
            'admissionType',
            'area',
            'family',
            'pet',
            'pet.species',
            'reason',
            'receptionist',
            'receptionType',
            'room',
            'vet',
            'currentStatusAppointment.attentionStatus',
            'currentStatusGrooming.groomingStatus',
            'currentStatusCremation.cremationStatus',
            'currentHospitalizationStatus.hospitalizationStatus',
            'currentHotelStatus.hotelStatus',
            'episode.account',
            'cremation',
            'transfersFrom.toReception.receptionType'

        )
            ->where('reception_type_id', $reception_type_id) //filtramos el tipo de recepcion de acuerdo al id recibido en la funcion
            // Fecha real de cada módulo: las 5 tablas ya muestran/usan
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('entry_date', '>=', $request->date);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('entry_date', '<=', $request->date_to);
            })
            ->when($reception_type_id == 4 && $request->filled('exit_date'), function ($query) use ($request) {
                $query->whereDate('exit_date', $request->exit_date);
            })
            // Estatus: cada módulo tiene su propio catálogo/historial: se
            // filtra por el ÚLTIMO estatus
            ->when($reception_type_id == 1 && $request->filled('status_id'), function ($query) use ($request) {
                $query->whereHas('currentStatusAppointment', function ($q) use ($request) {
                    $q->where('attention_status_id', $request->status_id);
                });
            })
            ->when($reception_type_id == 2 && $request->filled('status_id'), function ($query) use ($request) {
                $query->whereHas('currentHospitalizationStatus', function ($q) use ($request) {
                    $q->where('hospitalization_status_id', $request->status_id);
                });
            })
            ->when($reception_type_id == 3 && $request->filled('status_id'), function ($query) use ($request) {
                $query->whereHas('currentStatusGrooming', function ($q) use ($request) {
                    $q->where('grooming_status_id', $request->status_id);
                });
            })
            ->when($reception_type_id == 4 && $request->filled('status_id'), function ($query) use ($request) {
                $query->whereHas('currentHotelStatus', function ($q) use ($request) {
                    $q->where('hotel_status_id', $request->status_id);
                });
            })
            ->when($reception_type_id == 5 && $request->filled('status_id'), function ($query) use ($request) {
                $query->whereHas('currentStatusCremation', function ($q) use ($request) {
                    $q->where('cremation_status_id', $request->status_id);
                });
            })
            // Estatus de cuenta: independiente del estatus clínico y del tipo
            // de recepción, ya que Account cuelga de Episode, no del módulo.
            ->when($request->filled('account_status'), function ($query) use ($request) {
                $query->whereHas('episode.account', function ($q) use ($request) {
                    $q->where('status', $request->account_status);
                });
            })

            ->when(in_array($reception_type_id, [1, 3]) && $request->filled('vet_id'), function ($query) use ($request) {
                $query->where('veterinarian_id', $request->vet_id);
            })
            ->when($reception_type_id == 2 && $request->filled('area_id'), function ($query) use ($request) {
                $query->where('area_id', $request->area_id);
            })
            ->when($reception_type_id == 2 && $request->filled('admission_type_id'), function ($query) use ($request) {
                $query->where('admission_type_id', $request->admission_type_id);
            })
            ->get();

        // Episodios con al menos un traslado
        $episodesWithTransfers = Reception::whereIn('episode_id', $receptions->pluck('episode_id')->filter()->unique())
            ->whereHas('transfersFrom')
            ->pluck('episode_id')
            ->unique();

        $accountStatementService = $reception_type_id == 2 ? app(AccountStatementService::class) : null;

        // Precalienta ES_ALMACENABLE/nombre-precio de Firebird para TODOS los
        // episodios de esta página de una sola vez -sin esto,
        // hasConfirmedConsultaPayment() de abajo paga su propia consulta a
        // Firebird por cada fila de un episodio distinto (medido: 10
        // consultas Firebird para 8 filas). Ver AccountStatementService::
        // prewarmConsultaFirebirdData(). No cambia qué se calcula, solo evita
        // repetir la misma consulta por fila.
        $almacenablesPrewarm = $accountStatementService
            ? $accountStatementService->prewarmConsultaFirebirdData($receptions->pluck('episode_id'))
            : null;

        $receptions = $receptions->map(function ($reception) use ($episodesWithTransfers, $accountStatementService, $almacenablesPrewarm) {
            $reception->can_edit = auth()->user()->can('update', $reception);
            $reception->can_delete = auth()->user()->can('delete', $reception);
            $reception->has_transfers = $episodesWithTransfers->contains($reception->episode_id);

            // Mismo criterio que AssignmentController::appointments(): si el
            // estatus vigente es "Trasladado", la columna Estatus muestra
            // "Trasladado a {tipo de recepción destino}" en vez del nombre a
            // secas. Se calcula para todas las recepciones (no solo type=1)
            // porque transfersFrom ya se usaba para has_transfers; el label
            // solo lo consume la tabla de Consultas.
            $reception->transferred_to = $reception->transfersFrom->first()?->toReception?->receptionType?->name;

            // Solo Hospitalización: el botón "Documentos" del index se oculta
            // hasta que exista un anticipo confirmado por el total exacto de
            // la consulta trasladada (ver AccountStatementService::
            // hasConfirmedConsultaPayment()). Sin Consulta de por medio
            // (hospitalización directa), el método ya regresa true y no
            // afecta nada.
            if ($accountStatementService) {
                $reception->show_documents = $accountStatementService->hasConfirmedConsultaPayment($reception, $almacenablesPrewarm);
            }

            return $reception;
        });

        return DataTables::of($receptions)
            ->make(true);
    }

    public function documents(Reception $reception)
    {
        $documents = $reception->documents()
            ->with('formatType')
            ->latest()
            ->get();

        // Misma fuente de verdad que hospital_authorization()/
        // hospital_authorizationpdf()/SurgeryController (ver
        // ReceptionDocumentService) — el catálogo de responsivas
        // requeridas no se duplica aquí.
        $requiredFormats = $this->documentService->requiredFormatsFor($reception);
        $existingTypeIds = $documents->pluck('format_type_id');
        $formatTypeNames = FormatType::whereIn('id', array_keys($requiredFormats))->pluck('name', 'id');

        $missingFormats = collect($requiredFormats)
            ->reject(fn($meta, $formatTypeId) => $existingTypeIds->contains($formatTypeId))
            ->map(fn($meta, $formatTypeId) => [
                'format_type_id' => $formatTypeId,
                'name' => $formatTypeNames->get($formatTypeId, $meta['fallback_name']),
                // from=reception para las responsivas de Hospital y Cirugía:
                // este modal de Documentos es exclusivo de Recepción (ver
                // openDocumentsModal() en receptions/index.js, único
                // consumidor de este endpoint), así que marca a
                // hospital_authorization()/surgery_authorization() que, al
                // firmarse, deben volver aquí y no a Hospitalizaciones (ver
                // hospital_auth.js/auth_surgery.js). Grooming/hotel/cremación
                // no tienen ese problema de redirección, no se tocan.
                'url' => in_array($meta['route'], ['hospital.list', 'surgery.auth'], true)
                    ? route($meta['route'], ['id' => $reception->id, 'from' => 'reception'])
                    : route($meta['route'], $reception->id),
            ])
            ->values();

        return response()->json([
            'documents' => $documents->map(function ($document) {
                return [
                    'id' => $document->id,
                    'type' => $document->formatType->name,
                    'pdf' => $document->format_pdf,
                    'url' => $document->format_pdf_url,
                    'created_at' => $document->created_at->format('d/m/Y H:i'),
                ];
            }),
            'missing_formats' => $missingFormats,
            // Mismo Reception::family() (belongsTo Family, family_id) que ya
            // usa list()/el resto de las tablas del index — el modal de
            // Documentos lo necesita para "Enviar por WhatsApp"
            // (sendDocumentWhatsApp en receptions/index.js). Antes ese
            // teléfono nunca llegaba: openDocumentsModal(receptionId,
            // familyPhone) esperaba un segundo argumento que ningún
            // onclick="openDocumentsModal(${data.id})" del index le pasaba
            // -por eso el modal decía "sin teléfono" incluso cuando la
            // familia sí tenía uno registrado-. Ahora viaja en la misma
            // respuesta que el modal ya consume, sin depender de que cada
            // botón se lo pase por su cuenta.
            'family_phone' => $reception->family?->phone,
        ]);
    }

    public function bulkAdvanceStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:receptions,id',
        ]);

        $updated = [];
        $skipped = [];

        foreach ($request->ids as $receptionId) {
            $currentHistory = CremationStatusHistory::where('reception_id', $receptionId)
                ->latest('changed_at')
                ->first();

            if (!$currentHistory) {
                $skipped[] = $receptionId;
                continue;
            }

            $nextStatus = CremationStatus::where('id', '>', $currentHistory->cremation_status_id)
                ->orderBy('id')
                ->first();

            if (!$nextStatus) {
                $skipped[] = $receptionId;
                continue;
            }

            CremationStatusHistory::create([
                'reception_id' => $receptionId,
                'cremation_status_id' => $nextStatus->id,
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]);

            $updated[] = [
                'reception_id' => $receptionId,
                'new_status_id' => $nextStatus->id,
                'new_status_name' => $nextStatus->name,
            ];
        }

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'skipped' => $skipped,
        ]);
    }

    // public function historial($id)
    // {
    //     $receptions = Reception::with('receptionType', 'reason', 'vet')->where('pet_id', $id)->get();
    //     $redSheet=RedSheet::with('reception')->where('reception_id->pet_id', $id)->get();
    //     $surgery=Surgery::with('reception')->where('reception_id->pet_id', $id)->get();
    //     $prescription=Prescription::where('pet_id', $id)->get();
    //     return DataTables::of($receptions, $redSheet, $surgery,$prescription)->make(true);
    // }

    public function historial($id, Request $request)
    {
        //se recopilan todas las recpciones de la mascota
        $receptions = Reception::with('receptionType', 'reason', 'vet')
            ->where('pet_id', $id)
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('reception_type_id', $request->type);
            })
            ->when($request->filled('vet_id'), function ($query) use ($request) {
                $query->where('veterinarian_id', $request->vet_id);
            })
            ->get();

        //Pendiente de determinar
        // $redSheets = RedSheet::whereHas('reception', function ($query) use ($id) {
        //     $query->where('pet_id', $id);
        // })->with('reception')->get();

        // $surgeries = Surgery::whereHas('reception', function ($query) use ($id) {
        //     $query->where('pet_id', $id);
        // })->with('reception')->get();

        // $prescriptions = Prescription::where('pet_id', $id)->get();

        // $data = [];
        // foreach ($receptions as $reception) {
        //     $data[] = [
        //         'entry_date' => $reception->entry_date,
        //         'vet_name' => $reception->vet->name ?? '',
        //         'reception_type' => $reception->receptionType->name ?? '',
        //         'reason' => $reception->reason->name ?? '',
        //         'reception_id' => $reception->id,
        //         'pet_id' => $reception->pet_id,
        //         'redSheets' => $redSheets->pluck('description')->toArray(),
        //         'surgeries' => $surgeries->pluck('surgery_type')->toArray(),
        //         'prescriptions' => $prescriptions->pluck('medicine')->toArray(),
        //     ];
        // }

        return DataTables::of($receptions)->make(true); //se regresa para datable la info
    }



    public function hospital_authorization($id)
    {
        // Ya fue firmada: no volver a mostrar la responsiva en blanco. Misma
        // fuente de verdad que ya usa documents() para excluirla de
        // missing_formats (Format format_type_id=1 ligado a esta reception,
        // creado en hospital_authorizationpdf() al aceptar y firmar) — no se
        // agrega un campo/estado nuevo.
        $alreadyAuthorized = Format::where('reception_id', $id)
            ->where('format_type_id', 1)
            ->exists();

        if ($alreadyAuthorized) {
            $reception = Reception::find($id);
            $cameFromTransfer = ReceptionTransfer::where('to_reception_id', $id)->exists();
            $fromReception = request()->query('from') === 'reception';

            // Mismo destino que hospital_auth.js elige al terminar de firmar:
            // si a la recepción todavía le falta otra responsiva requerida
            // (ver ReceptionDocumentService::nextMissingFormat() — hoy solo
            // la quirúrgica, en área Quirúrgicos), se manda directo a
            // firmarla en vez de mostrar el formulario de Hospital otra vez.
            $nextFormat = $this->documentService->nextMissingFormat($reception);
            if ($nextFormat) {
                return redirect()->route($nextFormat['route'], array_filter([
                    'id' => $id,
                    'from' => $fromReception ? 'reception' : null,
                ]));
            }

            return redirect()
                ->route($fromReception || !$cameFromTransfer ? 'receptions.index' : 'assignment.hospital')
                ->with('success', 'Esta hospitalización ya cuenta con su autorización firmada.');
        }

        $reception = Reception::with('admissionType')->find($id); //buscamos la recepcion correspondiente

        // Si la hospitalización viene de un traslado desde Consulta, la
        // recepcionista debe cobrar el saldo pendiente de esa consulta antes
        // de poder generar/firmar la responsiva (ver
        // AccountStatementService::consultaBalance() y
        // AdvancePaymentController::payConsulta()). Si no hay Consulta de
        // por medio (hospitalización directa), el saldo es 0 y no bloquea.
        $consultaBalance = app(AccountStatementService::class)->consultaBalance($reception);
        if ($consultaBalance > 0) {
            return redirect()->route('receptions.index')
                ->with('error', 'Debes registrar el pago de la consulta ($' . number_format($consultaBalance, 2) . ') antes de generar la autorización.');
        }

        // NOTA: a propósito NO se bloquea aquí el acceso al formulario de
        // Hospital aunque todavía falte la Autorización de Procedimientos
        // Anestésicos y Quirúrgicos (área Quirúrgicos) — ambas responsivas
        // pueden firmarse en cualquier orden (ver modal de Documentos
        // Pendientes). Lo único que se condiciona a que estén las dos
        // completas es el paso real de estatus "Trasladado" ->
        // "Hospitalizado", que decide ReceptionDocumentService::
        // advanceToHospitalizadoIfComplete() dentro de
        // hospital_authorizationpdf()/SurgeryController::
        // surgery_authorizationpdf() -sin importar cuál de las dos se firme
        // al final-.

        $total = $this->admissionTypePrice($reception?->admissionType?->articulo_id);
        $cameFromTransfer = ReceptionTransfer::where('to_reception_id', $id)->exists();

        // Distingue si esta responsiva se abrió desde el modal de Documentos
        // de Recepción (ver documents()/requiredFormatsFor(), que arman esta
        // URL con ?from=reception) o desde store() al crear una
        // hospitalización directa (mismo marcador) -en ambos casos la
        // recepcionista debe volver a Recepciones al firmar, nunca a
        // Hospitalizaciones (assignment.hospital)-, versus el flujo propio
        // de Hospital, que no manda este parámetro y conserva su
        // comportamiento actual (ver hospital_auth.js).
        $fromReception = request()->query('from') === 'reception';

        // Sin esto, el botón "Atrás" del navegador puede restaurar esta
        // página (el formulario en blanco) desde su caché/bfcache SIN volver
        // a pedírsela al servidor — la condición de arriba nunca se vuelve a
        // evaluar y parece que "no se actualizó". no-store fuerza a que
        // siempre se re-consulte.
        return response()
            ->view('reception.pdf', compact("reception", "total", "cameFromTransfer", "fromReception"))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Precio de Microsip para el ARTICULO_ID de un tipo de admisión, o null
     * si no tiene articulo_id configurado o no se encontró el precio.
     */
    private function admissionTypePrice(?int $articuloId): ?string
    {
        if (!$articuloId) {
            return null;
        }

        $precio = DB::connection('firebird')
            ->table('ARTICULOS AS a')
            ->leftJoin('PRECIOS_ARTICULOS AS pa', 'a.ARTICULO_ID', '=', 'pa.ARTICULO_ID')
            ->where('a.ARTICULO_ID', $articuloId)
            ->value('pa.PRECIO');

        return $precio !== null ? number_format((float) $precio, 2, '.', '') : null;
    }

    public function hospital_authorizationpdf(Request $request, $id)
    {
        $reception = Reception::find($id); //buscamos la recepcion correspondiente
        $pet = Pet::with('family', 'genre')->find($reception->pet_id); //buscamos la mascota correspondiente
        $total = $request->input('total'); // recuperamos el total enviado
        $signatureDataUrl = $request->input('signature'); //recuperamos la firma del cliente

        // Generar el PDF con los datos de la recepción,  mascota y llenado del propietario
        $pdf = PDF::loadView('reception.pdf', [
            'reception' => $reception,
            'pet' => $pet,
            'total' => $total,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);

        // Guardar el PDF en el almacenamiento público.
        $pdfPath = 'public/receptions/AUT_HOSP_' . $id . '_' . $pet->id . '_' . date('Ymd_His') . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath); // Obtener la URL pública del PDF

        $format = new Format();
        $format->format_type_id = 1; // Tipo de formato: autorización hospitalaria
        $format->reception_id = $id; // Relacionado con la recepción
        $format->pet_id = $pet->id; // Relacionado con la mascota
        $format->format_pdf = $pdfPath; // Ruta del PDF almacenado
        $format->save();

        // La(s) responsiva(s) firmada(s) son lo que finalmente admite al
        // paciente: recién cuando ya no falta ninguna responsiva requerida
        // (ver ReceptionDocumentService::advanceToHospitalizadoIfComplete())
        // pasa de "Trasladado" a "Hospitalizado" (ver
        // ReceptionTransferController::seedInitialStatusHistory(), que ya no
        // siembra "Hospitalizado" al transferir). Mismo patrón que
        // HospitalizationController::discharge() para "Dado de alta". Una
        // hospitalización creada directamente (sin traslado) ya nace
        // "Hospitalizado" (ver ReceptionController::store()): el método no
        // hace nada en ese caso (evita sembrar una entrada duplicada). En
        // área Quirúrgicos, si además falta la Autorización de
        // Procedimientos Anestésicos y Quirúrgicos, el estatus se queda en
        // "Trasladado" hasta que también se firme esa (sin importar cuál de
        // las dos se firme primero) — ver SurgeryController::
        // surgery_authorizationpdf(), que llama al mismo método.
        $this->documentService->advanceToHospitalizadoIfComplete($reception);

        // Si a esta recepción todavía le falta otra responsiva requerida
        // (ver ReceptionDocumentService::nextMissingFormat() — hoy solo
        // aplica el caso Hospitalización en área Quirúrgicos, que además
        // exige la Autorización de Procedimientos Anestésicos y
        // Quirúrgicos), el frontend encadena directo a firmarla en vez de
        // volver a Recepciones (ver hospital_auth.js).
        $nextFormat = $this->documentService->nextMissingFormat($reception);
        $nextFormatUrl = $nextFormat
            ? route($nextFormat['route'], ['id' => $id, 'from' => 'reception'])
            : null;

        // Retornar la respuesta JSON con la URL del PDF y el ID del formato generado
        return response()->json([
            'url' => asset($pdfUrl),
            'format_id' => $format->id,
            'next_format_url' => $nextFormatUrl,
        ]);
    }


    public function getReceptionArea($id)
    {
        $reception = Reception::find($id); //busca recepcion correpondinete
        return response()->json(['area_id' => $reception->area_id]); //regresa el area que tiene registrada la recepcion
    }

    /**
     * Saldo pendiente de la Consulta del episodio de esta recepción (ver
     * AccountStatementService::consultaBalance()). Usado por el modal
     * "Pagar consulta" (openConsultaPaymentModal en advancePayment.js) para
     * mostrar el monto y fijar el mínimo permitido antes de enviarlo.
     */
    public function consultaBalance($id)
    {
        $reception = Reception::findOrFail($id);
        $balance = app(AccountStatementService::class)->consultaBalance($reception);

        return response()->json(['balance' => $balance]);
    }

    /**
     * Resumen combinado de Consulta + servicios hospitalarios para el modal
     * de pago de Recepción (ver openConsultaPaymentModal()/advancePayment.js
     * y AccountStatementService::paymentMinimumRequired()). Se recalcula
     * completo en cada llamada -nunca un valor guardado-, así que si el
     * médico agrega un servicio nuevo en Red Sheet después de que Recepción
     * ya abrió el modal, la siguiente consulta a este endpoint ya lo incluye.
     */
    public function paymentSummary($id)
    {
        $reception = Reception::findOrFail($id);
        $statementService = app(AccountStatementService::class);

        // Una sola vez cada uno (memoizados en AccountStatementService): el
        // resto de las llamadas de abajo (consultaBalance(),
        // hospitalizacionServiciosTotal(), hospitalizacionAnticipoRequerido(),
        // paymentMinimumRequired()) reutilizan este mismo cálculo en vez de
        // repetirlo — antes, entre las 4, hospitalizacionServiciosPreview()
        // se recalculaba completo 4 veces en una sola llamada a este endpoint.
        $serviciosConsulta = $statementService->consultaServiciosPreview($reception);
        $serviciosHospitalarios = $statementService->hospitalizacionServiciosPreview($reception);

        $consultaTotal = (float) $serviciosConsulta->sum('total');
        $hospitalizacionTotal = (float) $serviciosHospitalarios->sum('total');

        // La Consulta como concepto real de la lista (no un mensaje aparte):
        // misma estructura línea por línea que ya usa el Estado de Cuenta
        // (OrdenVentaService::previsualizar()), solo con una etiqueta 'tipo'
        // agregada para que el front pueda seguir separando qué es Consulta
        // y qué es servicio hospitalario dentro de la misma lista.
        $servicios = $serviciosConsulta->map(fn ($item) => ['tipo' => 'consulta'] + $item)
            ->concat($serviciosHospitalarios->map(fn ($item) => ['tipo' => 'hospitalizacion'] + $item))
            ->values();

        return response()->json([
            'servicios' => $servicios,
            'servicios_total' => $consultaTotal + $hospitalizacionTotal,
            'consulta' => [
                'total' => $consultaTotal,
                'balance' => $statementService->consultaBalance($reception),
            ],
            'hospitalizacion' => [
                // Se conserva para no romper nada que ya lea esta forma
                // específica (mismas líneas, sin la etiqueta 'tipo').
                'servicios' => $serviciosHospitalarios->values(),
                'servicios_total' => $hospitalizacionTotal,
                // TODO(negocio): sigue en 0.0 hasta que se defina la regla
                // de anticipo — ver AccountStatementService::
                // hospitalizacionAnticipoRequerido().
                'anticipo_requerido' => $statementService->hospitalizacionAnticipoRequerido($reception),
            ],
            'minimum_required' => $statementService->paymentMinimumRequired($reception),
        ]);
    }


    public function getFamilyByPet($pet_id)
    {
        $pet = Pet::find($pet_id); //encuentra a la mascota
        if ($pet && $pet->family) {
            return response()->json($pet->family); //cuando encuentra a la mascota y la relacion de familia, regrea la info
        }
        return response()->json(null, 404);
    }

    public function transfer(Request $request, $id)
    {
        $reception = Reception::with('admissionType')->findOrFail($id); // Buscar la recepción
        $this->authorize("update", $reception); //valida permisos de editrae

        $previousAdmissionTypeId = $reception->admission_type_id;
        $previousAdmissionType = $reception->admissionType?->name ?? 'Sin definir';

        $reception->update($request->validate([
            'admission_type_id' => 'integer|exists:admission_types,id',
        ]));  // Validar y actualizar el tipo de admisión

        $newAdmissionType = $reception->fresh('admissionType')->admissionType?->name ?? 'Sin definir';

        ReceptionEvent::create([
            'reception_id' => $reception->id,
            'event_type' => 'admission_change',
            'description' => "Cambio de admisión: {$previousAdmissionType} → {$newAdmissionType}",
            'from_admission_type_id' => $previousAdmissionTypeId,
            'to_admission_type_id' => $reception->admission_type_id,
            'created_by' => auth()->id(),
        ]);

        return response()->json($reception); // Retornar la recepción actualizada en formato JSON
    }

    public function cuenta($id)
    {
        $reception = Reception::with('pet')->where('id', $id)->first();  // Obtener la recepción con su mascota asociada
        $redSheets = RedSheet::where('reception_id', $id)->with('imaging', 'lab', 'service')->get(); // Obtener hojas rojas relacionadas con imagenología, laboratorio y servicios
        $surgeries = Surgery::where('reception_id', $id)->with('service')->get(); // Obtener cirugías asociadas con sus servicios

        // Construir el arreglo de datos
        $data = [
            'reception' => $reception,
            'redSheets' => $redSheets,
            'surgeries' => $surgeries,
        ];
        return DataTables::of($data)->make(true); // Retornar los datos formateados para DataTables
    }


    public function groomingservice(int $id)
    {
        // Crear instancias vacías de Grooming y GeneralGrooming y recopila catalogos necesarios
        $grooming = new Grooming();
        $generalGrooming = new GeneralGrooming();
        $products = Producto::where("ESTATUS",  "A")->get();
        $reception = Reception::find($id);
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::all();

        $this->authorize("create", Grooming::class); //verifica permisos para crear groomings
        // Retornar la vista con los datos necesario
        return view('grooming.create', compact('grooming', 'products', 'reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms', 'pets', 'generalGrooming'));
    }
}
