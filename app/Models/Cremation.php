<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cremation
 *
 * @property $id
 * @property $reception_id
 * @property $pet_id
 * @property $date_death
 * @property $date_finish
 * @property $servicie
 * @property $CM_id
 * @property $type_urn
 * @property $urn_model
 * @property $observations
 * @property $placa_type_id
 * @property $price
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Pet $pet
 * @property Reception $reception
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cremation extends Model
{
    use SoftDeletes, HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'pet_id', 'date_death', 'date_finish', 'servicie', 'CM_id', 'type_urn', 'vet_id', 'urn_model', 'observations', 'text_placa', 'placa_type_id', 'price', 'status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pet()
    {
        return $this->belongsTo(\App\Models\Pet::class, 'pet_id', 'id');
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
    public function cm()
    {
        return $this->belongsTo(\App\Models\CmType::class, 'CM_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(\App\Models\TagType::class, 'placa_type_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Precios::class, 'servicie', 'ARTICULO_ID');
    }

    public function serv()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'servicie', 'ARTICULO_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
    }

    public function cremationStatus()
    {
        return $this->belongsTo(CremationStatus::class);
    }
}
