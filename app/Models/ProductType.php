<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProductType
 *
 * @property $id
 * @property $product_classification_id
 * @property $microsip_id
 * @property $name
 * @property $price
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property ProductClassification $productClassification
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProductType extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['product_classification_id', 'microsip_id', 'name', 'price'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productClassification()
    {
        return $this->belongsTo(\App\Models\ProductClassification::class, 'product_classification_id', 'id');
    }
    

}
