<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cubicle
 *
 * @property $id
 * @property $name
 * @property $cubicle_type_id
 * @property $state
 * @property $created_at
 * @property $updated_at
 *
 * @property CubicleType $cubicleType
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cubicle extends Model
{
    

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'cubicle_type_id', 'state'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cubicleType()
    {
        return $this->belongsTo(\App\Models\CubicleType::class, 'cubicle_type_id', 'id');
    }
    

}
