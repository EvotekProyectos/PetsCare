<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PetWeight
 *
 * Historial append-only de mediciones de peso de una mascota (ver
 * PetWeightController::store()): cada medición nueva es una fila nueva,
 * nunca se edita ni se elimina una existente. pets.weight se sigue
 * actualizando en paralelo como "peso actual", solo por compatibilidad con
 * el resto del sistema.
 *
 * @property $id
 * @property $pet_id
 * @property $reception_id
 * @property $weight
 * @property $measured_at
 * @property $created_by
 * @property $created_at
 * @property $updated_at
 *
 * @property Pet $pet
 * @property Reception $reception
 * @property User $createdBy
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PetWeight extends Model
{
    use HasFactory;

    protected $fillable = ['pet_id', 'reception_id', 'weight', 'measured_at', 'created_by'];

    protected $casts = [
        'measured_at' => 'datetime',
    ];

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
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
}
