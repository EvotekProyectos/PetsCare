<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 *
 * @property $id
 * @property $vaccine
 * @property $deworming_internal
 * @property $deworming_external
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Service extends Model
{
    

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['vaccine', 'deworming_internal', 'deworming_external'];



}
