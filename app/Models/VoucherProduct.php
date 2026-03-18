<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VoucherProduct
 *
 * @property $id
 * @property $voucher_id
 * @property $product_id
 * @property $requested_quantity
 * @property $unit_of_measure
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Voucher $voucher
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class VoucherProduct extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['voucher_id', 'product_id', 'requested_quantity', 'unit_of_measure', 'red_sheet_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucher()
    {
        return $this->belongsTo(\App\Models\Voucher::class, 'voucher_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'product_id', 'ARTICULO_ID');
    }

    public function redSheet()
    {
        return $this->belongsTo(\App\Models\redSheet::class, 'red_sheet_id', 'id');
    }
}
