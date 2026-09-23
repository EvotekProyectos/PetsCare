<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BudgetDetail
 *
 * @property $id
 * @property $budget_id
 * @property $service_id
 * @property $price
 * @property $notes
 * @property $converted_at
 * @property $converted_to_type
 * @property $converted_to_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Budget $budget
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class BudgetDetail extends Model
{
    use SoftDeletes, HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['budget_id', 'service_id', 'lab_id', 'img_id', 'price', 'notes', 'type', 'converted_at', 'converted_to_type', 'converted_to_id'];

    protected $casts = [
        'converted_at' => 'datetime',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function budget()
    {
        return $this->belongsTo(\App\Models\Budget::class, 'budget_id', 'id');
    }

    /**
     * Servicio real generado a partir de esta línea (ver BudgetConversionService::convert()).
     * Hoy siempre es un RedSheet, pero se deja polimórfico por si en el
     * futuro se convierte a AppointmentService (conversión antes del traslado).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function convertedTo()
    {
        return $this->morphTo();
    }

    public function isConverted(): bool
    {
        return $this->converted_at !== null;
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Precios::class, 'service_id', 'ARTICULO_ID');
    }

    public function serv()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'service_id', 'ARTICULO_ID');
    }

    public function imaging()
    {
        return $this->belongsTo(\App\Models\Precios::class, 'img_id', 'ARTICULO_ID');
    }

    public function img()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'img_id', 'ARTICULO_ID');
    }

    public function lab()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'lab_id', 'ARTICULO_ID');
    }

    public function laboratory()
    {
        return $this->belongsTo(\App\Models\Precios::class, 'lab_id', 'ARTICULO_ID');
    }
    

}
