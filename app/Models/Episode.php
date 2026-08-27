<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Episode
 *
 * @property $id
 * @property $pet_id
 * @property $status
 * @property $opened_at
 * @property $closed_at
 * @property $created_by
 * @property $notes
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Pet $pet
 * @property Account $account
 * @property User $createdBy
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Episode extends Model
{
    use SoftDeletes, HasFactory;

    const STATUS_OPEN = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pet_id', 'status', 'opened_at', 'closed_at', 'created_by', 'notes'];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pet()
    {
        return $this->belongsTo(\App\Models\Pet::class, 'pet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receptions()
    {
        return $this->hasMany(\App\Models\Reception::class, 'episode_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne(\App\Models\Account::class, 'episode_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
}
