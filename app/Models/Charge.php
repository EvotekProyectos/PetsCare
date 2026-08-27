<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Charge
 *
 * @property $id
 * @property $account_id
 * @property $reception_id
 * @property $product_id
 * @property $description
 * @property $quantity
 * @property $unit_price
 * @property $discount
 * @property $tax
 * @property $total
 * @property $status
 * @property $cancelled_at
 * @property $cancelled_by
 * @property $cancel_reason
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Account $account
 * @property Reception $reception
 * @property User $cancelledBy
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Charge extends Model
{
    use SoftDeletes, HasFactory;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'reception_id',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'tax',
        'total',
        'status',
        'cancelled_at',
        'cancelled_by',
        'cancel_reason',
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'account_id', 'id');
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
    public function cancelledBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'cancelled_by', 'id');
    }
}
