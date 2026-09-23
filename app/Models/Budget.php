<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Budget
 *
 * @property $id
 * @property $pet_id
 * @property $date
 * @property $surgery_pack_id
 * @property $procedure
 * @property $procedure_price
 * @property $biometric
 * @property $biometric_price
 * @property $chemistry
 * @property $chemistry_price
 * @property $nodulectomy
 * @property $nodulectomy_price
 * @property $histopathology
 * @property $histopathology_price
 * @property $xrays
 * @property $xrays_price
 * @property $collar
 * @property $collar_price
 * @property $body
 * @property $body_price
 * @property $others
 * @property $total
 * @property $vet_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Pet $pet
 * @property SurgeryPack $surgeryPack
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Budget extends Model
{
    use SoftDeletes, HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pet_id', 'date',  'total', 'vet_id', 'others', 'reception_id', 'signed_at'];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * Ver BudgetController::budgetpdf() (donde se marca) y
     * BudgetConversionService (única fuente de verdad de qué presupuesto es
     * convertible a servicios de Hospitalización).
     */
    public function isSigned(): bool
    {
        return $this->signed_at !== null;
    }


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
    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(\App\Models\BudgetDetail::class, 'budget_id', 'id');
    }
}
