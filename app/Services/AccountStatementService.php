<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AdmissionType;
use App\Models\Appointment;
use App\Models\AttentionStatus;
use App\Models\AppointmentService;
use App\Models\Cremation;
use App\Models\CremationStatus;
use App\Models\CremationStatusHistory;
use App\Models\Grooming;
use App\Models\GroomingStatus;
use App\Models\GroomingStatusHistory;
use App\Models\Hotel;
use App\Models\Producto;
use App\Models\Reception;
use App\Models\ReceptionEvent;
use App\Models\ReceptionType;
use App\Models\RedSheet;
use App\Models\Surgery;
use App\Models\VaccineCertificate;
use App\Models\VoucherProduct;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Previsualización y cierre del "estado de cuenta" de una recepción,

 */
class AccountStatementService
{
    public function __construct(private OrdenVentaService $ordenVentaService)
    {
    }

    /**
     * Previsualización en vivo: NO crea ningún Charge.
     */
    public function preview(Reception $reception): array
    {
        // Agrupado por tipo de recepción (Consulta/Hospitalización/etc.)
        $groups = $this->articulosPorTipoFor($reception)->map(fn ($g) => [
            'reception_type' => $g['reception_type_name'],
            'items' => $this->ordenVentaService->previsualizar($g['articulos']),
        ])->values();

        $total = $groups->sum(fn ($g) => $g['items']->sum('total'));

        $account = $reception->episode->account ?? null;
        $salesOrder = $account ? $account->salesOrders()->latest('id')->first() : null;

        return [
            'reception' => [
                'id' => $reception->id,
                'pet_name' => $reception->pet->name ?? null,
                'family_name' => $reception->pet->family->name ?? null,
            ],
            'groups' => $groups,
            'total' => $total,
            'account_status' => $account->status ?? null,
            'sales_order' => $salesOrder ? [
                'folio' => $salesOrder->folio,
                'status' => $salesOrder->status,
            ] : null,
            'can_close' => $this->canClose($reception),
            'close_requirement' => $this->cannotCloseMessage($reception),
        ];
    }

    /**
     * Datos para el PDF informativo 
     */
    public function pdfData(Reception $reception): array
    {
        $reception->loadMissing(['pet.family', 'family', 'vet', 'receptionType']);

        $items = $this->ordenVentaService->previsualizar($this->articulosFor($reception));

        return [
            'reception' => $reception,
            'items' => $items,
            'total' => $items->sum('total'),
        ];
    }

    /**
     * Cierra la cuenta: persiste los Charge, genera la ODV en Microsip
     * (vía OrdenVentaService::generar(), que ya crea el SalesOrder) y
     * marca la Account como CLOSED.
     *
     * @throws \InvalidArgumentException si la recepción no está en condiciones de cerrarse.
     */
    public function close(Reception $reception): array
    {
        $account = $reception->episode->account ?? null;
        if (!$account) {
            throw new \InvalidArgumentException('Esta recepción no tiene una cuenta asociada.');
        }

        if ($account->status !== Account::STATUS_OPEN) {
            throw new \InvalidArgumentException('La cuenta de esta recepción ya no está abierta.');
        }

        if (!$this->canClose($reception)) {
            throw new \InvalidArgumentException($this->cannotCloseMessage($reception));
        }

        $articulos = $this->articulosFor($reception);
        if ($articulos->isEmpty()) {
            throw new \InvalidArgumentException('No hay servicios registrados para cobrar en esta recepción.');
        }

        $folio = $this->ordenVentaService->generar($reception->id, $articulos);

        $this->advanceStatusAfterClose($reception);

        $account->update([
            'status' => Account::STATUS_CLOSED,
            'closed_at' => now(),
        ]);

        return ['folio' => $folio];
    }

    /**
     * Artículos cobrables hasta ahora para TODO el episodio de la recepción
     * dada (no solo esa recepción): un episodio puede tener varias Reception
     * encadenadas por traslados (ver ReceptionTransferController), y todas
     * comparten la misma Account, así que se cobra el episodio completo.
     * Cada elemento es ['product_id' => ARTICULO_ID de Microsip, 'quantity' => int],
     * ya consolidado (un solo elemento por product_id, cantidades sumadas) para
     * que OrdenVentaService facture UNIDADES reales en vez de líneas repetidas.
     */
    public function articulosFor(Reception $reception): Collection
    {
        return $this->consolidarCantidades(
            $this->articulosPorTipoFor($reception)->flatMap(fn ($g) => $g['articulos'])
        );
    }

    /**
     * Como articulosFor(), pero conservando la agrupación por tipo de
     * recepción en vez de aplanarla — la usa preview() para mostrar
     * secciones separadas en el modal de Estado de Cuenta cuando el
     * episodio tuvo traslados (ej. Consulta -> Hospitalización). Consolida
     * cantidades DENTRO de cada tipo, no entre tipos: si el mismo producto
     * aparece en dos tipos, cada uno mantiene su propia línea (es solo
     * presentación, no afecta el cobro real — articulosFor() sí consolida
     * el resultado final entre todos los grupos, igual que antes).
     */
    private function articulosPorTipoFor(Reception $reception): Collection
    {
        $porTipo = Reception::where('episode_id', $reception->episode_id)
            ->get(['id', 'reception_type_id'])
            ->groupBy('reception_type_id');

        return $porTipo->map(function ($group, $typeId) {
            $ids = $group->pluck('id');
            $articulos = match ((int) $typeId) {
                1 => $this->consultaArticulos($ids),
                2 => $this->hospitalizacionArticulos($ids),
                3 => $this->groomingArticulos($ids),
                4 => $this->hotelArticulos($ids),
                5 => $this->cremacionArticulos($ids),
                default => collect(),
            };

            return [
                'reception_type_id' => (int) $typeId,
                'reception_type_name' => ReceptionType::find($typeId)?->name ?? 'Otro',
                'articulos' => $this->consolidarCantidades($articulos),
            ];
        })
            // Sin filtrar los grupos sin artículos: un tipo de recepción sin
            // servicios cobrables todavía (ej. Consulta trasladada de
            // inmediato a Hospitalización, sin nada registrado en ella) debe
            // seguir apareciendo como su propia sección — informativo, no se
            // oculta — el front ya la pinta como "Sin servicios" (ver
            // accountStatement.js). articulosFor() (close()/generar()) no se
            // ve afectado: flatMap sobre un grupo vacío no aporta nada.
            ->values();
    }

    /**
     * Agrupa por product_id y suma cantidades, por si el mismo artículo
     * viene de más de una fuente (ej. mismo estudio de lab pedido dos veces).
     */
    private function consolidarCantidades(Collection $articulos): Collection
    {
        return $articulos
            ->filter(fn ($item) => filled($item['product_id'] ?? null) && ($item['quantity'] ?? 0) > 0)
            ->groupBy('product_id')
            ->map(fn ($group, $productId) => [
                'product_id' => (int) $productId,
                'quantity' => (int) $group->sum('quantity'),
            ])
            ->values();
    }

    /**
     * Envuelve una lista plana de IDs en el formato ['product_id', 'quantity']
     * con cantidad 1 c/u, para los tipos que cobran una unidad por artículo.
     */
    private function conCantidadUno(Collection $ids): Collection
    {
        return $ids->filter()->map(fn ($id) => ['product_id' => $id, 'quantity' => 1]);
    }

    private function consultaArticulos(Collection $receptionIds): Collection
    {
        // Servicio de la consulta en sí (lo que el médico registra al terminar),
        // igual que Grooming.service_id / Hotel.service_type_id / Cremation.servicie.
        // pluck (no value): puede haber más de una Appointment en el episodio
        // si hubo un traslado de ida y vuelta a Consulta.
        $consultationServiceIds = Appointment::whereIn('reception_id', $receptionIds)->pluck('service_id');

        // Eliminados (deleted_at) ya quedan fuera automáticamente: SoftDeletes
        // no trae esas filas sin withTrashed().
        $appointmentServiceRows = AppointmentService::whereIn('reception_id', $receptionIds)
            ->where(function ($query) {
                $query->whereNotNull('imaging_type_id')
                    ->orWhereNotNull('lab_type_id');
            })
            ->get(['id', 'imaging_type_id', 'lab_type_id']);

        $servicesIds = $this->cobrableIds(
            $appointmentServiceRows,
            AppointmentService::class,
            fn ($item) => $item->imaging_type_id ?? $item->lab_type_id
        );

        $vaccinesIds = VaccineCertificate::whereIn('reception_id', $receptionIds)
            ->whereNotNull('product')
            ->pluck('product');

        $ids = $consultationServiceIds
            ->merge($servicesIds)
            ->merge($vaccinesIds)
            ->filter()
            ->values();

        return $this->conCantidadUno($ids);
    }

    /**
     * De un lote de filas origen (AppointmentService o RedSheet), filtra los
     * artículos que SÍ se pueden cobrar: los NO consumibles (ES_ALMACENABLE
     * != "S") se cobran igual que siempre; los consumibles solo si su vale
     * activo ya está Surtido (decisión de negocio: un consumible cuyo vale se
     * queda Pendiente, o se cancela/rechaza, nunca se cobra — ver
     * AppointmentController::store()/HospitalizationController::discharge(),
     * que cancelan cualquier vale Pendiente al finalizar/dar de alta).
     *
     * Producto vive en Firebird (otra conexión): no se puede resolver
     * ES_ALMACENABLE con un JOIN SQL, así que se resuelve en PHP con un
     * query batch (mismo patrón que VoucherProduct::activeMapFor(), que
     * también se reutiliza aquí tal cual para el estatus del vale).
     */
    private function cobrableIds(Collection $rows, string $sourceableType, \Closure $articuloIdOf): Collection
    {
        $articuloIds = $rows->map($articuloIdOf)->filter()->unique()->values();
        $almacenables = Producto::whereIn('ARTICULO_ID', $articuloIds)->pluck('ES_ALMACENABLE', 'ARTICULO_ID');
        $activeVouchers = VoucherProduct::activeMapFor($sourceableType, $rows->pluck('id')->all());

        return $rows->map(function ($row) use ($articuloIdOf, $almacenables, $activeVouchers) {
            $articuloId = $articuloIdOf($row);
            if (!$articuloId) {
                return null;
            }

            if (($almacenables->get($articuloId) ?? null) !== 'S') {
                return $articuloId;
            }

            $voucherStatus = $activeVouchers->get($row->id)?->voucher?->status;

            return $voucherStatus === 'Surtido' ? $articuloId : null;
        })->filter()->values()->toBase();
        // ->toBase(): si $rows viene vacío, Eloquent\Collection::map() no
        // hace el downcast automático a Collection base (solo lo hace cuando
        // detecta que el resultado ya no son Models), así que sin esto el
        // valor de retorno podía quedar como Eloquent\Collection vacía. Eso
        // truena más adelante en hospitalizacionArticulos() al hacer
        // ->merge() con una Collection base real: Eloquent\Collection::merge()
        // asume que cada item tiene ->getKey() (espera Models), y revienta
        // con "Call to a member function getKey() on int" en cuanto esa
        // Collection deja de estar vacía del otro lado del merge.
    }

    private function hospitalizacionArticulos(Collection $receptionIds): Collection
    {
        // Igual que RedSheetController::ordenventa(): RedSheet + Surgery + Cremation
        // (una hospitalización puede terminar en cremación sin cambiar de recepción).
        // whereNull('removed_at'): los marcados como eliminados (ver
        // RedSheetController::removeService()) siguen visibles en recap() por
        // diseño, pero no se cobran.
        $redSheetRows = RedSheet::whereIn('reception_id', $receptionIds)
            ->whereNull('removed_at')
            ->where(function ($query) {
                $query->whereNotNull('service_type_id')
                    ->orWhereNotNull('imaging_type_id')
                    ->orWhereNotNull('lab_type_id');
            })
            ->get(['id', 'service_type_id', 'imaging_type_id', 'lab_type_id']);

        $redSheetIds = $this->cobrableIds(
            $redSheetRows,
            RedSheet::class,
            fn ($item) => $item->service_type_id ?? $item->imaging_type_id ?? $item->lab_type_id
        );

        $surgeryIds = Surgery::whereIn('reception_id', $receptionIds)
            ->whereNotNull('product_type_id')
            ->pluck('product_type_id');

        $cremationIds = Cremation::whereIn('reception_id', $receptionIds)
            ->whereNotNull('servicie')
            ->pluck('servicie');

        // Cobro por día de hospitalización, a la tarifa del tramo de admisión
        // vigente en cada día (ver admisionDiasArticulos()): un articulo_id
        // REPETIDO una vez por día, para que conCantidadUno()/consolidarCantidades()
        // (ya existentes) sumen la cantidad real sin mecanismo nuevo.
        $diasIds = Reception::whereIn('id', $receptionIds)
            ->get()
            ->flatMap(fn (Reception $reception) => $this->admisionDiasArticulos($reception));

        $ids = $redSheetIds->merge($surgeryIds)->merge($cremationIds)->merge($diasIds)->filter()->values();

        return $this->conCantidadUno($ids);
    }

    /**
     * Reconstruye la línea de tiempo de admisiones de UNA recepción de
     * Hospitalización y devuelve el articulo_id de cada tramo repetido una
     * vez por día que abarca ese tramo.
     *
     * Punto de partida: admission_type_id original (from_admission_type_id
     * del primer ReceptionEvent admission_change, o el admission_type_id
     * actual si nunca cambió). Cada admission_change marca el fin de un
     * tramo y el inicio del siguiente (to_admission_type_id + su created_at).
     * Punto final: ver hospitalizacionFin().
     */
    private function admisionDiasArticulos(Reception $reception): Collection
    {
        if (!$reception->entry_date) {
            return collect();
        }

        $events = ReceptionEvent::where('reception_id', $reception->id)
            ->where('event_type', 'admission_change')
            ->orderBy('created_at')
            ->get();

        $boundaries = collect([$reception->entry_date])
            ->merge($events->pluck('created_at'))
            ->push($this->hospitalizacionFin($reception));

        $admissionTypeIds = collect([
            optional($events->first())->from_admission_type_id ?? $reception->admission_type_id,
        ])->merge($events->pluck('to_admission_type_id'));

        $articulosPorAdmision = AdmissionType::whereIn('id', $admissionTypeIds->filter()->unique())
            ->pluck('articulo_id', 'id');

        $articuloIds = collect();

        foreach ($admissionTypeIds as $index => $admissionTypeId) {
            $dias = $this->contarDias($boundaries[$index], $boundaries[$index + 1]);
            $articuloId = $admissionTypeId ? $articulosPorAdmision->get($admissionTypeId) : null;

            if ($articuloId) {
                $articuloIds = $articuloIds->concat(array_fill(0, $dias, $articuloId));
            }
        }

        return $articuloIds;
    }

    /**
     * Punto final del tramo de admisiones de una recepción:
     * - exit_date si ya hay alta.
     * - el created_at del ReceptionTransfer si fue trasladada a otra recepción
     *   (mismo u otro tipo): NO "ahora", porque de lo contrario se duplicarían
     *   los días ya contados por la recepción destino del mismo episodio
     *   (ver isTransferred()/transfersFrom(), ReceptionTransferController::store()
     *   no toca exit_date de la recepción de origen).
     * - "ahora" si sigue hospitalizado y no fue trasladada.
     */
    private function hospitalizacionFin(Reception $reception)
    {
        if ($reception->exit_date) {
            return $reception->exit_date;
        }

        $transfer = $reception->transfersFrom()->first();
        if ($transfer) {
            return $transfer->created_at;
        }

        return now();
    }

    /**
     * Misma fórmula de day_count ya usada en el resto del sistema (ver
     * red-sheet/create.blade.php): normaliza ambas fechas a medianoche y
     * suma 1. Cómo contar el día actual sin terminar queda pendiente de
     * definir (ver tarea de negocio); por ahora se reutiliza tal cual, sin
     * ajuste especial.
     */
    private function contarDias($start, $end): int
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->startOfDay();

        return max(1, $start->diffInDays($end) + 1);
    }

    private function groomingArticulos(Collection $receptionIds): Collection
    {
        $ids = Grooming::whereIn('reception_id', $receptionIds)
            ->whereNotNull('service_id')
            ->pluck('service_id')
            ->filter()
            ->values();

        return $this->conCantidadUno($ids);
    }

    private function hotelArticulos(Collection $receptionIds): Collection
    {
        // Cada fila de Hotel (la estancia inicial y cada extensión) se cobra
        // PRECIO_UNITARIO × number_days de ESA fila, no 1 unidad fija: son
        // los días acordados para ese tramo (ver HotelController::store()/
        // storeExtension()), y una extensión puede tener un service_type_id
        // distinto al de la estancia inicial.
        return Hotel::whereIn('reception_id', $receptionIds)
            ->whereNotNull('service_type_id')
            ->get(['service_type_id', 'number_days'])
            ->map(fn ($hotel) => [
                'product_id' => $hotel->service_type_id,
                'quantity' => max(1, (int) $hotel->number_days),
            ])
            ->values();
    }

    private function cremacionArticulos(Collection $receptionIds): Collection
    {
        $ids = Cremation::whereIn('reception_id', $receptionIds)
            ->whereNotNull('servicie')
            ->pluck('servicie')
            ->filter()
            ->values();

        return $this->conCantidadUno($ids);
    }

    /**
     * Condición de "puede cerrarse" por tipo. Cada tipo tiene su propia
     * señal real de "servicio terminado" (ver diagnóstico previo); no son
     * intercambiables entre sí. Se evalúa SIEMPRE sobre la recepción vigente
     * del episodio (la que no ha sido trasladada a otra) — una recepción
     * intermedia ya trasladada nunca puede cerrarse de forma independiente,
     * sin importar su propio estatus.
     */
    private function canClose(Reception $reception): bool
    {
        $account = $reception->episode->account ?? null;
        if (!$account || $account->status !== Account::STATUS_OPEN) {
            return false;
        }

        $current = Reception::currentForEpisode($reception->episode_id);
        if (!$current || $current->id !== $reception->id) {
            return false;
        }

        return match ((int) $current->reception_type_id) {
            // Consulta: atendida/finalizada. Buscado por nombre, no
            // hardcodeado — mismo criterio que Grooming/Cremación abajo.
            1 => (int) optional($current->currentStatusAppointment)->attention_status_id
                === AttentionStatus::where('name', 'Finalizada')->value('id'),
            // Hospitalización: HospitalizationStatusHistory es andamiaje nuevo sin
            // historial confiable en datos viejos; exit_date es la señal real de alta.
            2 => $current->exit_date !== null,
            // Grooming y Cremación: "Creado" (recién capturado, aún no
            // pagado) es lo que HABILITA el cierre — INVERSO a Consulta/
            // Hospitalización/Hotel, que exigen servicio terminado. La cuenta
            // se cobra ANTES de empezar a trabajar el servicio; close() abajo
            // es lo único que avanza a "En espera"/"En espera de recolectar"
            // una vez cobrado (ver también ReceptionController::store() y
            // CremationController::store(), que siembran "Creado" al crear).
            3 => (int) optional($current->currentStatusGrooming)->grooming_status_id
                === GroomingStatus::where('name', 'Creado')->value('id'),
            // Hotel: la última fila de Hotel (puede haber extensiones) con fecha de salida.
            4 => optional($this->latestHotel($current->id))->finish_date !== null,
            5 => (int) optional($current->currentStatusCremation)->cremation_status_id
                === CremationStatus::where('name', 'Creado')->value('id'),
            default => false,
        };
    }

    private function cannotCloseMessage(Reception $reception): string
    {
        $current = Reception::currentForEpisode($reception->episode_id);

        if ($current && $current->id !== $reception->id) {
            return 'Esta recepción fue trasladada; el cierre de cuenta ahora se gestiona desde la recepción vigente del episodio.';
        }

        return match ((int) ($current->reception_type_id ?? $reception->reception_type_id)) {
            1 => 'Solo se puede cerrar la cuenta cuando la consulta está en estatus "Finalizada".',
            2 => 'Solo se puede cerrar la cuenta cuando el paciente ya fue dado de alta.',
            3 => 'Solo se puede cerrar la cuenta cuando el grooming está recién creado, antes de iniciar el servicio.',
            4 => 'Solo se puede cerrar la cuenta cuando la estancia tiene fecha de salida registrada.',
            5 => 'Solo se puede cerrar la cuenta cuando la cremación está recién creada, antes de iniciar el servicio.',
            default => 'No se puede cerrar la cuenta de este tipo de recepción.',
        };
    }

    private function latestHotel(int $receptionId): ?Hotel
    {
        return Hotel::where('reception_id', $receptionId)->latest('id')->first();
    }

    /**
     * Tras cerrar la cuenta exitosamente, Grooming y Cremación pasan de
     * "Creado" a "En espera"/"En espera de recolectar": recién ahí el
     * servicio puede empezar a trabajarse (ver canClose() arriba). Consulta/
     * Hospitalización/Hotel no se tocan — su cierre ya ocurre con el
     * servicio terminado, no antes.
     */
    private function advanceStatusAfterClose(Reception $reception): void
    {
        if ((int) $reception->reception_type_id === 3) {
            GroomingStatusHistory::create([
                'reception_id' => $reception->id,
                'grooming_status_id' => GroomingStatus::where('name', 'En espera')->value('id'),
            ]);
        }

        if ((int) $reception->reception_type_id === 5) {
            CremationStatusHistory::create([
                'reception_id' => $reception->id,
                'cremation_status_id' => CremationStatus::whereRaw('TRIM(name) = ?', ['En espera de recolectar'])->value('id'),
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]);
        }
    }
}
