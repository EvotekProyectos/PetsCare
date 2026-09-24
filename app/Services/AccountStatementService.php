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
    /**
     * Memoización por episode_id, con vida solo mientras dure esta instancia
     * (una petición HTTP típica resuelve un AccountStatementService nuevo,
     * así que esto nunca sobrevive entre requests ni sirve datos obsoletos).
     * Antes, una sola llamada a ReceptionController::paymentSummary()
     * terminaba recalculando hospitalizacionServiciosPreview() desde cero
     * 4 veces (directo + vía hospitalizacionServiciosTotal() +
     * hospitalizacionAnticipoRequerido() + paymentMinimumRequired()) — cada
     * una re-consultando RedSheet/Surgery/Cremation/AdmissionType/
     * VoucherProduct otra vez. Memoizar aquí, no en cada método por
     * separado, hace que ese recómputo desaparezca sin tocar ninguna regla
     * de negocio: cada método sigue haciendo exactamente el mismo cálculo,
     * solo una vez por episodio en vez de una vez por llamada.
     */
    private array $consultaServiciosCache = [];
    private array $hospitalizacionServiciosCache = [];
    private array $currentReceptionCache = [];

    public function __construct(private OrdenVentaService $ordenVentaService)
    {
    }

    /**
     * Previsualización en vivo: NO crea ningún Charge.
     */
    public function preview(Reception $reception): array
    {
        // Agrupado por tipo de recepción (Consulta/Hospitalización/etc.)
        $gruposArticulos = $this->articulosPorTipoFor($reception);

        // Precalienta el cache de Firebird (OrdenVentaService::
        // precalentarArticulos()) con la UNIÓN de ARTICULO_ID de TODOS los
        // grupos antes del loop de abajo: un episodio con traslado (ej.
        // Consulta -> Hospitalización) tiene 2+ grupos, y antes cada
        // previsualizar() del loop resolvía sus propios faltantes contra
        // Firebird por separado -hasta una consulta a Firebird por grupo-.
        // Con esto, el previsualizar() de cada grupo encuentra su cache ya
        // tibio, sin importar cuántos grupos tenga el episodio. Mismo TTL
        // (300s) y mismo mecanismo de siempre, solo se llena antes.
        $this->ordenVentaService->precalentarArticulos(
            $gruposArticulos->flatMap(fn ($g) => $g['articulos'])->pluck('product_id')->unique()
        );

        $groups = $gruposArticulos->map(fn ($g) => [
            'reception_type' => $g['reception_type_name'],
            'items' => $this->ordenVentaService->previsualizar($g['articulos']),
        ])->values();

        $total = $groups->sum(fn ($g) => $g['items']->sum('total'));

        $account = $reception->episode->account ?? null;
        $salesOrder = $account ? $account->salesOrders()->latest('id')->first() : null;
        $advancePayments = $this->advancePaymentsFor($account);

        return [
            'reception' => [
                'id' => $reception->id,
                'pet_name' => $reception->pet->name ?? null,
                'family_name' => $reception->pet->family->name ?? null,
                // Mismo campo/relación que ya usa ReceptionController::
                // documents() para el botón "Enviar por WhatsApp" del modal
                // de Documentos — se agrega aquí para que el modal de Estado
                // de Cuenta pueda ofrecer el mismo botón sin una consulta
                // aparte ni asumir un teléfono.
                'family_phone' => $reception->pet->family->phone ?? null,
            ],
            'groups' => $groups,
            'total' => $total,
            'account_status' => $account->status ?? null,
            'sales_order' => $salesOrder ? [
                'folio' => $salesOrder->folio,
                'status' => $salesOrder->status,
            ] : null,
            'advance_payments' => $this->advancePaymentsSummary($advancePayments),
            'advance_payments_total' => $this->advancePaymentsTotal($advancePayments),
            'can_close' => $this->canClose($reception),
            'close_requirement' => $this->cannotCloseMessage($reception),
        ];
    }

    /**
     * Datos para el PDF informativo
     */
    public function pdfData(Reception $reception): array
    {
        $reception->loadMissing(['pet.family', 'family', 'vet', 'receptionType', 'episode.account']);

        $items = $this->ordenVentaService->previsualizar($this->articulosFor($reception));
        $advancePayments = $this->advancePaymentsFor($reception->episode->account ?? null);

        return [
            'reception' => $reception,
            'items' => $items,
            'total' => $items->sum('total'),
            'advance_payments' => $this->advancePaymentsSummary($advancePayments),
            'advance_payments_total' => $this->advancePaymentsTotal($advancePayments),
        ];
    }

    /**
     * Anticipos ya ligados a la cuenta de esta recepción (ver
     * AdvancePaymentController::store() / advance-payments:backfill-accounts).
     * Puramente informativo por ahora: no se restan del total ni se filtran
     * por status, porque status=1 ("pagado") todavía no lo pone nada en el
     * sistema — cuando exista ese flujo, esto deberá filtrar por status=1.
     */
    private function advancePaymentsFor(?Account $account): Collection
    {
        if (!$account) {
            return collect();
        }

        return $account->advancePayments()->orderBy('date')->get();
    }

    private function advancePaymentsSummary(Collection $advancePayments): Collection
    {
        return $advancePayments->map(fn ($advancePayment) => [
            'id' => $advancePayment->id,
            'reference' => $advancePayment->reference,
            'concept' => $advancePayment->concept,
            'date' => $advancePayment->date,
            'amount' => (float) $advancePayment->amount,
        ])->values();
    }

    private function advancePaymentsTotal(Collection $advancePayments): float
    {
        return (float) $advancePayments->sum(fn ($advancePayment) => (float) $advancePayment->amount);
    }

    /**
     * Saldo pendiente de la porción de Consulta del episodio de $reception:
     * total cobrable de las Reception tipo Consulta (1) del episodio, menos
     * los anticipos ya registrados en la cuenta (Episode hasOne Account: es
     * la MISMA cuenta que seguirá usando la Hospitalización si hubo un
     * traslado, así que cualquier anticipo ya pagado cuenta aquí sin
     * importar en qué recepción del episodio se haya capturado).
     *
     * Usado para exigir el pago de la consulta antes de admitir la
     * hospitalización (ver ReceptionController::hospital_authorization() y
     * AdvancePaymentController::payConsulta()). Si el episodio no tiene
     * ninguna Reception de Consulta (ej. hospitalización sin traslado
     * previo), el total es 0 y no bloquea nada.
     */
    public function consultaBalance(Reception $reception): float
    {
        $account = $reception->episode->account ?? null;
        if (!$account) {
            return 0.0;
        }

        $pagado = $this->advancePaymentsTotal($this->advancePaymentsFor($account));

        return max(0.0, $this->consultaTotal($reception) - $pagado);
    }

    /**
     * Líneas (nombre/cantidad/precio/total, vía OrdenVentaService::previsualizar())
     * de TODOS los servicios de Consulta actualmente registrados en el
     * episodio de $reception — mismo criterio/estructura que
     * hospitalizacionServiciosPreview(), para poder representar la Consulta
     * como un concepto real (no un mensaje aparte) en el Modal de Pago (ver
     * ReceptionController::paymentSummary()). Memoizado por episode_id: ver
     * comentario en las propiedades de la clase.
     */
    public function consultaServiciosPreview(Reception $reception, ?Collection $almacenables = null): Collection
    {
        $episodeId = $reception->episode_id;

        if (array_key_exists($episodeId, $this->consultaServiciosCache)) {
            return $this->consultaServiciosCache[$episodeId];
        }

        $consultaReceptionIds = Reception::where('episode_id', $episodeId)
            ->where('reception_type_id', 1)
            ->pluck('id');

        return $this->consultaServiciosCache[$episodeId] = $this->ordenVentaService->previsualizar(
            $this->consolidarCantidades($this->consultaArticulos($consultaReceptionIds, $almacenables))
        );
    }

    /**
     * Total cobrable (sin restar anticipos) de la porción de Consulta del
     * episodio de $reception. 0.0 si el episodio no tiene ninguna Reception
     * de Consulta (ej. hospitalización sin traslado previo).
     *
     * $almacenables: ver prewarmConsultaFirebirdData()/cobrableIds() — mapa
     * ES_ALMACENABLE ya resuelto para inyectar en vez de consultar Firebird
     * de nuevo. Opcional, no cambia el resultado, solo de dónde sale el dato.
     */
    private function consultaTotal(Reception $reception, ?Collection $almacenables = null): float
    {
        return (float) $this->consultaServiciosPreview($reception, $almacenables)->sum('total');
    }

    /**
     * Líneas (nombre/cantidad/precio/total, vía OrdenVentaService::previsualizar())
     * de TODOS los servicios hospitalarios actualmente agregados al episodio
     * de $reception -mismos artículos que hospitalizacionArticulos()/close(),
     * evaluados en el momento en que se llama, nunca un valor guardado: si el
     * médico agrega un servicio nuevo, la siguiente llamada ya lo incluye-.
     * Vacía si el episodio no tiene ninguna Reception de Hospitalización.
     */
    public function hospitalizacionServiciosPreview(Reception $reception): Collection
    {
        $episodeId = $reception->episode_id;

        if (array_key_exists($episodeId, $this->hospitalizacionServiciosCache)) {
            return $this->hospitalizacionServiciosCache[$episodeId];
        }

        $hospitalizacionReceptionIds = Reception::where('episode_id', $episodeId)
            ->where('reception_type_id', 2)
            ->pluck('id');

        $result = $hospitalizacionReceptionIds->isEmpty()
            ? collect()
            : $this->ordenVentaService->previsualizar(
                $this->consolidarCantidades($this->hospitalizacionArticulos($hospitalizacionReceptionIds))
            );

        return $this->hospitalizacionServiciosCache[$episodeId] = $result;
    }

    /**
     * Total cobrable (sin restar anticipos) de TODOS los servicios
     * hospitalarios actuales del episodio de $reception. 0.0 si no hay
     * ninguno agregado todavía (o no hay ninguna Reception de Hospitalización).
     */
    public function hospitalizacionServiciosTotal(Reception $reception): float
    {
        return (float) $this->hospitalizacionServiciosPreview($reception)->sum('total');
    }

    /**
     * Anticipo requerido sobre los servicios hospitalarios actuales.
     *
     * TODO(negocio): la regla de anticipo (porcentaje, monto fijo, tabla por
     * tipo de servicio, etc.) todavía NO está definida — no se debe asumir
     * ningún valor. Mientras tanto se devuelve 0.0: no se exige ningún
     * anticipo, así que paymentMinimumRequired() de abajo se reduce al
     * saldo de consulta de siempre (sin cambio de comportamiento). Este es
     * el ÚNICO lugar que hay que tocar para incorporar la regla real —
     * AdvancePaymentController::payHospitalizacion() y el modal de
     * Recepción ya la consumen desde aquí, no hace falta tocarlos.
     */
    public function hospitalizacionAnticipoRequerido(Reception $reception): float
    {
        $serviciosTotal = $this->hospitalizacionServiciosTotal($reception);

        // TODO(negocio): aplicar aquí la regla real, ej.:
        //   return round($serviciosTotal * 0.5, 2);
        // Por ahora, sin regla definida, no se exige anticipo.
        return 0.0;
    }

    /**
     * Monto mínimo que Recepción debe cobrar para considerar "resuelto" el
     * caso de una hospitalización (trasladada o directa): saldo pendiente
     * de la consulta + anticipo de servicios hospitalarios. Ya NO es una
     * condición de acceso a Red Sheet (ver RedSheetController::entry(), que
     * dejó de depender del pago) — es puramente informativo/de cobro para
     * Recepción (ver AdvancePaymentController::payHospitalizacion()).
     */
    public function paymentMinimumRequired(Reception $reception): float
    {
        return $this->consultaBalance($reception) + $this->hospitalizacionAnticipoRequerido($reception);
    }

    /**
     * Para el botón "Documentos" del Index de Hospitalización: si la
     * hospitalización viene de una Consulta trasladada, ese botón debe
     * quedar oculto hasta que exista un anticipo CONFIRMADO (status=1, ver
     * AdvancePaymentController::payConsulta()) cuyo monto sea exactamente el
     * total de esa consulta — no basta con que el saldo llegue a 0 sumando
     * varios anticipos parciales. Si no hay ninguna Consulta en el episodio
     * (hospitalización directa), esta regla no aplica y no bloquea nada.
     *
     * $almacenables: opcional — ver prewarmConsultaFirebirdData(). Usado por
     * ReceptionController::list() (índice de Hospitalización) para resolver
     * el ES_ALMACENABLE de TODOS los episodios visibles en una sola consulta
     * a Firebird antes del loop, en vez de que cada fila pague la suya. Sin
     * este argumento (ej. llamado directo, sin prewarm) el comportamiento es
     * exactamente el mismo de siempre: cobrableIds() resuelve por su cuenta.
     */
    public function hasConfirmedConsultaPayment(Reception $reception, ?Collection $almacenables = null): bool
    {
        $consultaTotal = $this->consultaTotal($reception, $almacenables);
        if ($consultaTotal <= 0) {
            return true;
        }

        $account = $reception->episode->account ?? null;
        if (!$account) {
            return false;
        }

        // round(): amount es decimal(8,2) — evita falsos negativos por
        // arrastre de precisión de punto flotante en la suma de $consultaTotal.
        return $account->advancePayments()
            ->where('status', 1)
            ->where('amount', round($consultaTotal, 2))
            ->exists();
    }

    /**
     * Precalienta, para TODOS los episodios dados de una sola vez, lo que
     * hasConfirmedConsultaPayment() necesita de Firebird -pensado
     * exclusivamente para ReceptionController::list() (índice de
     * Hospitalización), que la llama una vez por fila: sin esto, cada fila
     * de un episodio distinto pagaba su propia consulta a Firebird para lo
     * mismo (medido: 10 consultas Firebird para 8 filas).
     *
     * Resuelve dos cosas, cada una por su propio motivo:
     * 1) ES_ALMACENABLE (cobrableIds()): se resuelve aquí mismo con
     *    Producto::whereIn() sobre la unión de artículos candidatos de
     *    TODOS los episodios, y el mapa resultante se inyecta en las
     *    llamadas de abajo. No se puede lograr esto con un simple
     *    "precalentamiento" de cache: CachedFirebirdBuilder cachea por
     *    SQL+bindings EXACTOS, así que el whereIn de un episodio individual
     *    (subconjunto) nunca encuentra en cache lo que ya se resolvió para
     *    la unión completa -de ahí que haga falta inyectar el mapa
     *    directamente en vez de confiar en el cache existente-.
     * 2) Nombre/precio (OrdenVentaService::productosCacheados(), cacheado
     *    por ARTICULO_ID individual): para esto SÍ basta con
     *    precalentarArticulos() (ya existente, sin tocar), porque ese cache
     *    sí es por ID y un precalentamiento con la unión sirve para
     *    cualquier subconjunto posterior.
     *
     * No cambia qué se calcula ni el resultado de hasConfirmedConsultaPayment()
     * para ninguna recepción -solo de dónde sale el dato ES_ALMACENABLE-.
     *
     * @return Collection|null el mapa ES_ALMACENABLE a pasar a cada llamada
     *   de hasConfirmedConsultaPayment(), o null si ningún episodio dado
     *   tiene ninguna Reception de Consulta (nada que precalentar).
     */
    public function prewarmConsultaFirebirdData(Collection $episodeIds): ?Collection
    {
        $consultaReceptionIds = Reception::whereIn('episode_id', $episodeIds->filter()->unique())
            ->where('reception_type_id', 1)
            ->pluck('id');

        if ($consultaReceptionIds->isEmpty()) {
            return null;
        }

        // Mismas filas candidatas que cobrableIds() usa dentro de
        // consultaArticulos() (ver esa función), pero para TODOS los
        // episodios de una sola consulta -se necesita el mapa ES_ALMACENABLE
        // ANTES de poder inyectarlo, así que este query puntual sí se repite
        // aquí (una sola vez por carga de tabla, no por fila).
        $articuloIds = AppointmentService::whereIn('reception_id', $consultaReceptionIds)
            ->where(function ($query) {
                $query->whereNotNull('imaging_type_id')
                    ->orWhereNotNull('lab_type_id');
            })
            ->get(['imaging_type_id', 'lab_type_id'])
            ->map(fn ($item) => $item->imaging_type_id ?? $item->lab_type_id)
            ->filter()
            ->unique()
            ->values();

        $almacenables = $articuloIds->isEmpty()
            ? collect()
            : Producto::whereIn('ARTICULO_ID', $articuloIds)->pluck('ES_ALMACENABLE', 'ARTICULO_ID');

        // Con $almacenables ya resuelto, esta llamada NO vuelve a tocar
        // Firebird para ES_ALMACENABLE (se lo inyectamos) -se usa solo para
        // obtener la lista consolidada de product_id de TODOS los episodios
        // y precalentar productosCacheados() en una sola pasada.
        $articulos = $this->consultaArticulos($consultaReceptionIds, $almacenables);
        $this->ordenVentaService->precalentarArticulos($articulos->pluck('product_id'));

        return $almacenables;
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

    private function consultaArticulos(Collection $receptionIds, ?Collection $almacenables = null): Collection
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
            fn ($item) => $item->imaging_type_id ?? $item->lab_type_id,
            $almacenables
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
     *
     * $almacenables opcional: si ya viene resuelto (ver
     * prewarmConsultaFirebirdData()), se usa tal cual y NO se vuelve a
     * consultar Firebird -CachedFirebirdBuilder cachea por SQL+bindings
     * exactos, así que un whereIn con un subconjunto de IDs no encuentra en
     * cache lo que ya se resolvió para un conjunto más grande; inyectar el
     * mapa es la única forma de evitar repetir esta consulta por cada
     * episodio-. Sin este argumento, el comportamiento es idéntico al de
     * siempre.
     */
    private function cobrableIds(Collection $rows, string $sourceableType, \Closure $articuloIdOf, ?Collection $almacenables = null): Collection
    {
        $articuloIds = $rows->map($articuloIdOf)->filter()->unique()->values();
        $almacenables ??= Producto::whereIn('ARTICULO_ID', $articuloIds)->pluck('ES_ALMACENABLE', 'ARTICULO_ID');
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

        $current = $this->currentReceptionForEpisode($reception->episode_id);
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
        $current = $this->currentReceptionForEpisode($reception->episode_id);

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

    /**
     * Reception::currentForEpisode() memoizado: canClose() y
     * cannotCloseMessage() lo pedían cada uno por separado dentro de la
     * misma llamada a preview() — mismo motivo que las otras memoizaciones
     * de esta clase.
     */
    private function currentReceptionForEpisode(int $episodeId): ?Reception
    {
        if (array_key_exists($episodeId, $this->currentReceptionCache)) {
            return $this->currentReceptionCache[$episodeId];
        }

        return $this->currentReceptionCache[$episodeId] = Reception::currentForEpisode($episodeId);
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
