<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VoucherProduct
 *
 * @property $id
 * @property $voucher_id
 * @property $product_id
 * @property $requested_quantity
 * @property $unit_of_measure
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Voucher $voucher
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class VoucherProduct extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['voucher_id', 'product_id', 'requested_quantity', 'unit_of_measure', 'sourceable_id', 'sourceable_type'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucher()
    {
        return $this->belongsTo(\App\Models\Voucher::class, 'voucher_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'product_id', 'ARTICULO_ID');
    }

    /**
     * Origen del producto vale: RedSheet (Hospitalización), AppointmentService
     * (Consulta) o, a futuro, Grooming.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sourceable()
    {
        return $this->morphTo();
    }

    /**
     * Mapa sourceable_id => VoucherProduct (con su voucher cargado) para todo
     * un lote de ids de un mismo origen, en una sola query — pensado para
     * listados completos (ej. tabla de servicios de una recepción), no para
     * llamarse fila por fila. "Activo" = el voucher no está Cancelado ni
     * Rechazado; un voucher recién creado (status NULL, antes de firmarse en
     * generate()) no cuenta como activo, igual que el flag que reemplaza.
     */
    public static function activeMapFor(string $sourceableType, array $sourceableIds): \Illuminate\Support\Collection
    {
        if (empty($sourceableIds)) {
            return collect();
        }

        return static::whereIn('sourceable_id', $sourceableIds)
            ->where('sourceable_type', $sourceableType)
            ->whereHas('voucher', fn ($q) => $q->whereNotIn('status', ['Cancelado', 'Rechazado']))
            ->with('voucher:id,folio,status')
            ->get()
            ->keyBy('sourceable_id');
    }

    /**
     * Mapa sourceable_id => Collection<VoucherProduct> con TODOS los vales de
     * cada fila (activos e históricos: cancelados/rechazados incluidos), en
     * una sola query para todo el lote — mismo cuidado de N+1 que activeMapFor().
     * Puede haber más de un vale por fila (uno cancelado, luego uno nuevo).
     */
    public static function historyMapFor(string $sourceableType, array $sourceableIds): \Illuminate\Support\Collection
    {
        if (empty($sourceableIds)) {
            return collect();
        }

        return static::whereIn('sourceable_id', $sourceableIds)
            ->where('sourceable_type', $sourceableType)
            ->with('voucher:id,folio,status,cancellation_reason,rejection_reason')
            ->get()
            ->groupBy('sourceable_id');
    }
}
