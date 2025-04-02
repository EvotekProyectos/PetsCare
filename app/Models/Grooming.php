<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Grooming
 *
 * @property $id
 * @property $reception_id
 * @property $service_id
 * @property $notes
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Reception $reception
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Grooming extends Model
{
    use SoftDeletes;
    use HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'service_id', 'notes'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Precios::class, 'service_id', 'ARTICULO_ID');
    }

    public function serv()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'service_id', 'ARTICULO_ID');
    }
    

}
