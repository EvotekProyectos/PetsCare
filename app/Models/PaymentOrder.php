<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory, HasFactory;

    protected $table = 'payment_orders';
    protected $fillable = ['reception_id', 'folio_odv'];


    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
}
