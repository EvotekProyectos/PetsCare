<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RedSheet
 *
 * @property $id
 * @property $reception_id
 * @property $lab_type_id
 * @property $imaging_type_id
 * @property $service_type_id
 * @property $observations
 * @property $day_count
 * @property $vet_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property ProductType $productType
 * @property ProductType $productType
 * @property Reception $reception
 * @property ProductType $productType
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RedSheet extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'lab_type_id', 'imaging_type_id', 'service_type_id', 'observations', 'day_count', 'vet_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function imaging()
    {
        return $this->belongsTo(\App\Models\ProductType::class, 'imaging_type_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lab()
    {
        return $this->belongsTo(\App\Models\ProductType::class, 'lab_type_id', 'id');
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
    public function service()
    {
        return $this->belongsTo(\App\Models\ProductType::class, 'service_type_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
    }
    

}
