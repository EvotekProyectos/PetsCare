<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends FDBModel
{
    use HasFactory;
    const CREATED_AT = 'FECHA_HORA_CREACION';
    const UPDATED_AT = 'FECHA_HORA_ULT_MODIF';
    
    protected $table = 'ARTICULOS';
    protected $primaryKey = 'ARTICULO_ID';
    protected $guarded = [];
}
