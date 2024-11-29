<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Surgery
 *
 * @property $id
 * @property $reception_id
 * @property $surgery_type_id
 * @property $surgery_date
 * @property $surgery_description
 * @property $preanesthetic
 * @property $anesthetic
 * @property $other_medicines
 * @property $treatment
 * @property $observations
 * @property $complications
 * @property $vet_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Reception $reception
 * @property ProductType $productType
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Surgery extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'product_type_id', 'date', 'observations',  'vet_id'];


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
    public function surgery()
    {
        return $this->belongsTo(\App\Models\ProductType::class, 'product_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
    }

    public function redSheet()
    {
        return $this->belongsTo(RedSheet::class, 'reception_id', 'reception_id');
    }
}
