<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precios extends FDBModel
{
    use HasFactory;
    const CREATED_AT = 'FECHA_HORA_CREACION';
    const UPDATED_AT = 'FECHA_HORA_ULT_MODIF';

    protected $table = 'PRECIOS_ARTICULOS';
    protected $primaryKey = 'PRECIO_ARTICULO_ID';
    protected $guarded = [];
}
