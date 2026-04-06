<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Voucher
 *
 * @property $id
 * @property $folio
 * @property $status
 * @property $reception_id
 * @property $vet_id
 * @property $issuer_id
 * @property $issued_at
 * @property $generated_document
 * @property $warehouse_observations
 * @property $cancellation_reason
 * @property $rejection_reason
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property User $user
 * @property Reception $reception
 * @property User $user
 * @property VoucherProduct[] $voucherProducts
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Voucher extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['folio', 'status', 'reception_id', 'vet_id', 'issuer_id', 'issued_at', 'vet_signature', 'warehouse_signature', 'generated_document', 'warehouse_observations', 'cancellation_reason', 'rejection_reason', 'cancellation_signature', 'cancelled_by'];

    protected $appends = ['generated_document_url'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function issuer()
    {
        return $this->belongsTo(\App\Models\User::class, 'issuer_id', 'id');
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
    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucherProducts()
    {
        return $this->hasMany(\App\Models\VoucherProduct::class, 'voucher_id',  'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cancelled()
    {
        return $this->belongsTo(\App\Models\User::class, 'cancelled_by', 'id');
    }

    public static function buildFolio($receptionId, $ignoreVoucherId = null)
    {
        $year = now()->year;

        $query = self::whereYear('created_at', $year);

        if ($ignoreVoucherId) {
            $query->where('id', '!=', $ignoreVoucherId);
        }

        $count = $query->count() + 1;

        return "VAL-{$year}-" . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getGeneratedDocumentUrlAttribute()
    {
        if (!$this->generated_document) {
            return null;
        }

        return asset('storage/' . str_replace('public/', '', $this->generated_document));
    }
}
