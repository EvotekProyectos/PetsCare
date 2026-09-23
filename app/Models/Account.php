<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Account
 *
 * @property $id
 * @property $episode_id
 * @property $status
 * @property $closed_at
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Episode $episode
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Account extends Model
{
    use SoftDeletes, HasFactory;

    const STATUS_OPEN = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';
    const STATUS_PAID = 'PAID';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['episode_id', 'status', 'closed_at'];

    protected $casts = [
        'closed_at' => 'datetime',
         'opened_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function episode()
    {
        return $this->belongsTo(\App\Models\Episode::class, 'episode_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function charges()
    {
        return $this->hasMany(\App\Models\Charge::class, 'account_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'account_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salesOrders()
    {
        return $this->hasMany(\App\Models\SalesOrder::class, 'account_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function advancePayments()
    {
        return $this->hasMany(\App\Models\AdvancePayment::class, 'account_id', 'id');
    }

    /**
     * Único total real de una cuenta: no existe ningún campo persistido con
     * el monto (ni en Account ni en SalesOrder) — Charge.total
     * creado por OrdenVentaService::generar() al cerrar la cuenta
     * (ver AccountStatementService::close()). Una cuenta OPEN legítimamente
     * no tiene Charges todavía, así que su suma es 0 hasta que se cierre.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeCharges()
    {
        return $this->hasMany(\App\Models\Charge::class, 'account_id', 'id')
            ->where('status', \App\Models\Charge::STATUS_ACTIVE);
    }

    /**
     * Mismo patrón hasOne(...)->latestOfMany() ya usado en Reception
     * (currentHotelStatus(), currentStatusGrooming(), etc.).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestSalesOrder()
    {
        return $this->hasOne(\App\Models\SalesOrder::class, 'account_id', 'id')->latestOfMany();
    }

    /**
     * Anticipo más reciente de la cuenta, por su fecha efectiva (no por id/
     * created_at) — es la fecha que el usuario capturó/el backend asignó al
     * registrar el anticipo (ver AdvancePaymentController::store()).
     * Usado por NotifyOverdueAdvancePayments para la regla de 48 horas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestAdvancePayment()
    {
        return $this->hasOne(\App\Models\AdvancePayment::class, 'account_id', 'id')->latestOfMany('date');
    }
}
