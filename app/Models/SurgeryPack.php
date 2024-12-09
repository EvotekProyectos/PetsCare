<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SurgeryPack
 *
 * @property $id
 * @property $name
 * @property $total
 * @property $catheterization
 * @property $catheterization_price
 * @property $preanesthetic
 * @property $preanesthetic_price
 * @property $monitoring
 * @property $monitoring_price
 * @property $surgical_clothing
 * @property $surgical_clothing_price
 * @property $preparations
 * @property $preparations_price
 * @property $observation
 * @property $observation_price
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SurgeryPack extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'total', 'catheterization_price', 'preanesthetic_price', 'monitoring_price', 'surgical_clothing_price','preparations_price', 'observation_price'];



}
