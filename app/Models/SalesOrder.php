<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SalesOrder
 *
 * @property $id
 * @property $account_id
 * @property $microsip_docto_id
 * @property $folio
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Account $account
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SalesOrder extends Model
{
    use HasFactory;

    const STATUS_GENERATED = 'GENERATED';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['account_id', 'microsip_docto_id', 'folio', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'account_id', 'id');
    }
}
