<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReceptionTransfer
 *
 * @property $id
 * @property $from_reception_id
 * @property $to_reception_id
 * @property $reason
 * @property $created_by
 * @property $created_at
 * @property $updated_at
 *
 * @property Reception $fromReception
 * @property Reception $toReception
 * @property User $createdBy
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ReceptionTransfer extends Model
{
    use HasFactory;

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['from_reception_id', 'to_reception_id', 'reason', 'created_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromReception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'from_reception_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toReception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'to_reception_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
}
