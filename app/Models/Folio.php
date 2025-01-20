<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folio extends FDBModel
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = 'FOLIOS_CAJAS';
    protected $primaryKey = 'CAJA_ID';
    protected $guarded = [];
}
