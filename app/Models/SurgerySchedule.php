<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SurgerySchedule
 *
 * @property $id
 * @property $family_id
 * @property $pet_id
 * @property $surgical_procedures_type_id
 * @property $day
 * @property $hour
 * @property $veterinarian_id
 * @property $status_surgery_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Family $family
 * @property Pet $pet
 * @property StatusSurgery $statusSurgery
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SurgerySchedule extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id','family_id', 'pet_id', 'surgical_procedures_type_id', 'day', 'hour','number_ticket', 'veterinarian_id', 'status_surgery_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo(\App\Models\Family::class, 'family_id', 'id');
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
    public function statusSurgery()
    {
        return $this->belongsTo(\App\Models\StatusSurgery::class, 'status_surgery_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'veterinarian_id', 'id');
    }
    
       /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    
    public function producto()
{
    return $this->belongsTo(\App\Models\Producto::class, 'surgical_procedures_type_id', 'ARTICULO_ID');
} 

}
