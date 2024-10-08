<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Prescription
 *
 * @property $id
 * @property $reception_id
 * @property $veterinarian_id
 * @property $recepcionist_id
 * @property $date
 * @property $medicine
 * @property $diagnosis
 * @property $observations
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property User $user
 * @property Reception $reception
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Prescription extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'veterinarian_id', 'recepcionist_id', 'date', 'medicine', 'diagnosis', 'observations'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receptionist()
    {
        return $this->belongsTo(\App\Models\User::class, 'recepcionist_id', 'id');
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
         return $this->belongsTo(\App\Models\User::class, 'veterinarian_id', 'id');
     }
    

}
