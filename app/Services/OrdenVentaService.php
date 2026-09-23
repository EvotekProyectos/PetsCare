<?php

namespace App\Services;

use App\Models\Charge;
use App\Models\Folio;
use App\Models\GenericModel;
use App\Models\PaymentOrder;
use App\Models\Reception;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrdenVentaService
{
    /**
     * Previsualización de nombre/precio de un conjunto de artículos consultado
     * directo en Microsip. Es de solo lectura: no crea Charge, PaymentOrder,
     * SalesOrder ni nada en DOCTOS_PV/DOCTOS_PV_DET.
     *
     * $articulos: iterable de ['product_id' => ARTICULO_ID, 'quantity' => int]
     * (ya consolidado por AccountStatementService, un elemento por product_id).
     */
    public function previsualizar(iterable $articulos): Collection
    {
        $articulos = $this->normalizarArticulos($articulos);
        if ($articulos->isEmpty()) {
            return collect();
        }

        $productos = $this->productosCacheados($articulos->pluck('product_id'));

        return $articulos->map(function ($item) use ($productos) {
            $producto = $productos->get($item['product_id']);
            $unitPrice = $producto ? floatval($producto['PRECIO']) : 0.0;
            $quantity = $item['quantity'];

            return [
                'product_id' => $item['product_id'],
                'description' => $producto['NOMBRE'] ?? null,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $unitPrice * $quantity,
            ];
        })->values();
    }

    /**
     * Nombre/precio de un lote de ARTICULO_ID, cacheado 300s POR ARTICULO_ID
     * individual (no por la combinación completa que se pida cada vez, que
     * casi nunca se repite igual entre dos recepciones distintas) — mismo
     * TTL ya usado en AppointmentController::consultation() y
     * BudgetDetailController::price(). Antes, cada llamada a previsualizar()
     * pagaba una conexión nueva a Firebird (~2-20s medidos) SIN cachear;
     * llamado una vez por fila desde AccountStatementService (ver
     * consultaTotal()/hasConfirmedConsultaPayment(), usado por el índice de
     * Hospitalización) esto era un N+1 real contra Firebird. Solo se
     * consulta Firebird para los ARTICULO_ID que todavía no estén en cache;
     * el resto se resuelve de una sola vez con whereIn().
     */
    private function productosCacheados(Collection $ids): Collection
    {
        $ids = $ids->unique()->values();
        $resultado = collect();
        $faltantes = collect();

        foreach ($ids as $id) {
            $cached = Cache::get("firebird_articulo_{$id}");

            if ($cached !== null) {
                $resultado->put($id, $cached);
            } else {
                $faltantes->push($id);
            }
        }

        if ($faltantes->isNotEmpty()) {
            // PRECIOS_ARTICULOS puede tener más de una fila por ARTICULO_ID
            // (varias listas de precio); ->unique() se queda con una sola,
            // igual que el ->first() del código original, para no duplicar
            // el total por el JOIN.
            $encontrados = DB::connection('firebird')
                ->table('ARTICULOS AS a')
                ->leftJoin('PRECIOS_ARTICULOS AS pa', 'a.ARTICULO_ID', '=', 'pa.ARTICULO_ID')
                ->whereIn('a.ARTICULO_ID', $faltantes->all())
                ->select('a.NOMBRE', 'a.ARTICULO_ID', 'pa.PRECIO')
                ->get()
                ->unique('ARTICULO_ID')
                ->keyBy('ARTICULO_ID');

            foreach ($encontrados as $id => $row) {
                $data = ['NOMBRE' => $row->NOMBRE, 'PRECIO' => $row->PRECIO];
                Cache::put("firebird_articulo_{$id}", $data, 300);
                $resultado->put($id, $data);
            }
        }

        return $resultado;
    }

    /**
     * Acepta tanto el formato nuevo (['product_id' => , 'quantity' => ]) como
     * una lista plana de IDs (compat: cantidad 1 c/u), agrupando por
     * product_id y sumando cantidades para no facturar el mismo artículo en
     * líneas separadas.
     */
    private function normalizarArticulos(iterable $articulos): Collection
    {
        return collect($articulos)
            ->map(function ($item) {
                if (is_array($item)) {
                    return [
                        'product_id' => $item['product_id'] ?? null,
                        'quantity' => (int) ($item['quantity'] ?? 1),
                    ];
                }

                return ['product_id' => $item, 'quantity' => 1];
            })
            ->filter(fn ($item) => filled($item['product_id']) && $item['quantity'] > 0)
            ->groupBy('product_id')
            ->map(fn ($group, $productId) => [
                'product_id' => $productId,
                'quantity' => $group->sum('quantity'),
            ])
            ->values();
    }

    /**
     * Genera una Orden de Venta en Microsip a partir de una recepción y una
     * colección de artículos a cobrar (ver normalizarArticulos(): acepta
     * ['product_id' => ARTICULO_ID, 'quantity' => int] o una lista plana de
     * IDs, cantidad 1 c/u). UNIDADES en Microsip y quantity del Charge
     * reflejan la cantidad real (ej. Hotel cobra number_days, no 1 fijo).
     */
    public function generar(int $reception, iterable $articulos): string
    {
        $articulos = $this->normalizarArticulos($articulos);

        // La cuenta del episodio es donde se registran los Charge y el SalesOrder
        $receptionModel = Reception::with('episode.account')->findOrFail($reception);
        $account = $receptionModel->episode->account ?? null;
        if (!$account) {
            throw new \Exception("La recepción #{$reception} no tiene un Account asociado (episode/account faltante).");
        }

        // Foleador para las Ordenes del Punto de Venta
        $folio = Folio::select(['CONSECUTIVO'])
            ->where('CAJA_ID', 170159)
            ->firstOrFail()->CONSECUTIVO;
        $newFolio = 'N' . str_pad($folio + 1, 8, '0', STR_PAD_LEFT);
        Folio::where('CAJA_ID', 170159)->increment('CONSECUTIVO', 1);

        $now = Carbon::now();

        // UNA sola consulta trayendo TODOS los artículos de golpe
        $productos = DB::connection('firebird')
            ->table('ARTICULOS AS a')
            ->leftJoin('PRECIOS_ARTICULOS AS pa', 'a.ARTICULO_ID', '=', 'pa.ARTICULO_ID')
            ->leftJoin('CLAVES_ARTICULOS AS ca', 'a.ARTICULO_ID', '=', 'ca.ARTICULO_ID')
            ->whereIn('a.ARTICULO_ID', $articulos->pluck('product_id')->all())
            ->select('a.NOMBRE', 'a.ARTICULO_ID', 'pa.PRECIO', 'ca.CLAVE_ARTICULO')
            ->get()
            ->keyBy('ARTICULO_ID'); // para buscar rápido por ID en el siguiente loop

        // Validar que no falte ningún artículo ANTES de armar nada
        $faltantes = $articulos->pluck('product_id')->diff($productos->keys());
        if ($faltantes->isNotEmpty()) {
            throw new \Exception("No se encontraron los siguientes artículos en Microsip: " . $faltantes->implode(', '));
        }

        $listadoPartidas = [];
        $chargesData = [];
        $importeNeto = 0;

        foreach ($articulos->values() as $key => $item) {
            $producto = $productos[$item['product_id']];
            $quantity = $item['quantity'];
            $unitPrice = floatval($producto->PRECIO);
            $totalNetoProducto = $unitPrice * $quantity;

            $listadoPartidas[] = [
                'CLAVE_ARTICULO' => $producto->CLAVE_ARTICULO,
                'ARTICULO_ID' => $producto->ARTICULO_ID,
                'UNIDADES' => $quantity,
                'UNIDADES_DEV' => 0,
                'TIPO_CONTAB_UNID' => 0,
                'PRECIO_UNITARIO' => $unitPrice,
                'PRECIO_UNITARIO_IMPTO' => $unitPrice,
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

            $chargesData[] = [
                'account_id' => $account->id,
                'reception_id' => $reception,
                'product_id' => $producto->ARTICULO_ID,
                'description' => $producto->NOMBRE,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => 0,
                'tax' => 0,
                'total' => $totalNetoProducto,
                'status' => Charge::STATUS_ACTIVE,
            ];

            $importeNeto += $totalNetoProducto;
        }

        // Los Charge se persisten ANTES de tocar Microsip: son el registro local
        // de trazabilidad, independientemente de si la ODV se logra generar.
        $charges = DB::transaction(function () use ($chargesData) {
            return collect($chargesData)->map(fn ($data) => Charge::create($data));
        });

        $ordenFields = [
            'CAJA_ID' => 170159,
            'TIPO_DOCTO' => 'O',
            'SUCURSAL_ID' => 54057,
            'FOLIO' => $newFolio,
            'FECHA' => $now->format('Y-m-d'),
            'HORA' => $now->format('H:i:s'),
            'CAJERO_ID' => 170160,
            'CLIENTE_ID' => 860,
            'ALMACEN_ID' => 953,
            'MONEDA_ID' => 1,
            'IMPUESTO_INCLUIDO' => 'S',
            'TIPO_CAMBIO' => 1,
            'TIPO_DSCTO' => 'P',
            'DSCTO_PCTJE' => 0,
            'DSCTO_IMPORTE' => 0,
            'ESTATUS' => 'N',
            'APLICADO' => 'S',
            'SISTEMA_ORIGEN' => 'PV',
        ];

        $basePVModel = new GenericModel('DOCTOS_PV', 'DOCTO_PV_ID');
        $detPVModel = new GenericModel('DOCTOS_PV_DET', 'DOCTO_PV_DET_ID');
        $basePVModel->timestamps = false;
        $detPVModel->timestamps = false;

        try {
            $ordenId = $basePVModel->createGeneric($ordenFields);

            foreach ($listadoPartidas as $partida) {
                $partida['DOCTO_PV_ID'] = $ordenId;
                $detPVModel->createGeneric($partida);
            }
        } catch (\Throwable $e) {
            // La ODV no se generó en Microsip: los Charge quedan cancelados,
            // no facturados, para no dejar cargos activos huérfanos.
            Charge::whereIn('id', $charges->pluck('id'))->update([
                'status' => Charge::STATUS_CANCELLED,
                'cancelled_at' => Carbon::now(),
                'cancel_reason' => 'Falló la generación de la ODV en Microsip: ' . $e->getMessage(),
            ]);

            throw $e;
        }

        PaymentOrder::create([
            'reception_id' => $reception,
            'folio_odv' => $newFolio,
        ]);

        SalesOrder::create([
            'account_id' => $account->id,
            'microsip_docto_id' => $ordenId,
            'folio' => $newFolio,
            'status' => SalesOrder::STATUS_GENERATED,
        ]);

        return $newFolio;
    }
}
