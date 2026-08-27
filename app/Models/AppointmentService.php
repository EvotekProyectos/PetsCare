<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AppointmentService
 *
 * @property $id
 * @property $reception_id
 * @property $lab_type_id
 * @property $imaging_type_id
 * @property $observations
 * @property $vet_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property ProductType $productType
 * @property ProductType $productType
 * @property Reception $reception
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AppointmentService extends Model
{
    use SoftDeletes, HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'lab_type_id', 'imaging_type_id', 'observations', 'vet_id', 'deleted_by', 'delete_reason'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function imaging()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'imaging_type_id', 'ARTICULO_ID');
    }
    
    public function img()
    {
        return $this->belongsTo(\App\Models\Precios::class, 'imaging_type_id', 'ARTICULO_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lab()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'lab_type_id', 'ARTICULO_ID');
    }

    public function laboratory()
    {
        return $this->belongsTo(\App\Models\Precios::class, 'lab_type_id', 'ARTICULO_ID');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function voucherProducts()
    {
        return $this->morphMany(\App\Models\VoucherProduct::class, 'sourceable');
    }

}
