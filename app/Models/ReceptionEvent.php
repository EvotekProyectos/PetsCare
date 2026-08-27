<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReceptionEvent
 *
 * Bitácora append-only de sucesos de una recepción (cambio de admisión,
 * seguimiento agregado, traslado, ...). Genérica y reutilizable por
 * cualquier tipo de recepción, no exclusiva de Hospitalización.
 *
 * @property $id
 * @property $reception_id
 * @property $event_type
 * @property $description
 * @property $followup_type
 * @property $followup_id
 * @property $from_admission_type_id
 * @property $to_admission_type_id
 * @property $created_by
 * @property $created_at
 *
 * @property Reception $reception
 * @property User $createdBy
 * @property AdmissionType $fromAdmissionType
 * @property AdmissionType $toAdmissionType
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ReceptionEvent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reception_id',
        'event_type',
        'description',
        'followup_type',
        'followup_id',
        'from_admission_type_id',
        'to_admission_type_id',
        'created_by',
    ];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromAdmissionType()
    {
        return $this->belongsTo(\App\Models\AdmissionType::class, 'from_admission_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toAdmissionType()
    {
        return $this->belongsTo(\App\Models\AdmissionType::class, 'to_admission_type_id', 'id');
    }
}
