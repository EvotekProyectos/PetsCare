<?php

namespace App\Models;

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
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pet_id', 'date',  'total', 'vet_id', 'notes', 'reception_id'];


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
}
