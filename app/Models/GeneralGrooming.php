<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GeneralGrooming
 *
 * @property $id
 * @property $reception_id
 * @property $instructions
 * @property $next_service
 * @property $critic_status
 * @property $delivery_service
 * @property $delivery_references
 * @property $folio
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Reception $reception
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class GeneralGrooming extends Model
{
    use SoftDeletes;
    use HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'instructions', 'next_service', 'critic_status', 'delivery_service', 'delivery_references', 'folio'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    

}
