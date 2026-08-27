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
use App\Models\User;
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
            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'reception_type_id' => 2,
                    'redirect' => route('hospital.list', ['id' => $reception->id]),
                ]);
            }
            return redirect()->route('hospital.list', ['id' => $reception->id])
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
     *Usado por el polling
     * del index para saber si hay que recargar la tabla del tab activo.
     */
    public function lastUpdateGlobal()
    {
        $lastChange = collect([
            Reception::max('updated_at'),
            ReceptionStatusHistory::max('updated_at'),
            GroomingStatusHistory::max('updated_at'),
            CremationStatusHistory::max('updated_at'),
            HospitalizationStatusHistory::max('updated_at'),
        ])
            ->filter()
            ->max();

        return response()->json([
            'last_update' => $lastChange,
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
            'cremation'

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

        $receptions = $receptions->map(function ($reception) use ($episodesWithTransfers) {
            $reception->can_edit = auth()->user()->can('update', $reception);
            $reception->can_delete = auth()->user()->can('delete', $reception);
            $reception->has_transfers = $episodesWithTransfers->contains($reception->episode_id);
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

        $existingTypeIds = $documents->pluck('format_type_id');

        $requiredFormats = $this->requiredFormatsFor($reception);
        $formatTypeNames = FormatType::whereIn('id', array_keys($requiredFormats))->pluck('name', 'id');

        $missingFormats = collect($requiredFormats)
            ->reject(fn ($meta, $formatTypeId) => $existingTypeIds->contains($formatTypeId))
            ->map(fn ($meta, $formatTypeId) => [
                'format_type_id' => $formatTypeId,
                'name' => $formatTypeNames->get($formatTypeId, $meta['fallback_name']),
                'url' => route($meta['route'], $reception->id),
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
        ]);
    }

    /**
     * Responsivas requeridas según el tipo de recepción, con la ruta de la
     * vista de captura/firma correspondiente. Consulta (1) no requiere
     * ninguna. Hospitalización (2) además requiere la quirúrgica (3) SOLO
     * si hay una Surgery asociada — se evalúa aparte, no es fija como las
     * demás. fallback_name es solo por si el catálogo FormatType no trae
     * el registro esperado (no debería pasar, ver FormatTypeSeeder).
     */
    private function requiredFormatsFor(Reception $reception): array
    {
        $required = match ((int) $reception->reception_type_id) {
            2 => [1 => ['route' => 'hospital.list', 'fallback_name' => 'Autorización para Hospitalización']],
            3 => [4 => ['route' => 'grooming.sign', 'fallback_name' => 'Responsiva Grooming']],
            4 => [5 => ['route' => 'hotel.format', 'fallback_name' => 'Responsiva Pensión']],
            5 => [7 => ['route' => 'cremation.responsiva', 'fallback_name' => 'Responsiva cremación']],
            default => [],
        };

        if ((int) $reception->reception_type_id === 2 && Surgery::where('reception_id', $reception->id)->exists()) {
            $required[3] = ['route' => 'surgery.auth', 'fallback_name' => 'Autorización de Procedimientos Anestésicos y Quirúrgicos'];
        }

        return $required;
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
        $reception = Reception::with('admissionType')->find($id); //buscamos la recepcion correspondiente
        $total = $this->admissionTypePrice($reception?->admissionType?->articulo_id);
        $cameFromTransfer = ReceptionTransfer::where('to_reception_id', $id)->exists();

        return view('reception.pdf', compact("reception", "total", "cameFromTransfer")); //enviamos los datos a la vista del pfd para firma y llendo
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
        $pdfPath = 'public/receptions/reception_' . $id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath); // Obtener la URL pública del PDF

        $format = new Format();
        $format->format_type_id = 1; // Tipo de formato: autorización hospitalaria
        $format->reception_id = $id; // Relacionado con la recepción
        $format->pet_id = $pet->id; // Relacionado con la mascota
        $format->format_pdf = $pdfPath; // Ruta del PDF almacenado
        $format->save();

        // Retornar la respuesta JSON con la URL del PDF y el ID del formato generado
        return response()->json(['url' => asset($pdfUrl), 'format_id' => $format->id]);
    }


    public function getReceptionArea($id)
    {
        $reception = Reception::find($id); //busca recepcion correpondinete 
        return response()->json(['area_id' => $reception->area_id]); //regresa el area que tiene registrada la recepcion
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
