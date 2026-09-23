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

    // Ver CachedFirebirdBuilder: cachea 300s cualquier lectura (where(),
    // find(), relaciones belongsTo hacia este modelo, etc.) para no pagar
    // una conexión a Firebird nueva cada vez que se resuelve un producto.
    public function newEloquentBuilder($query)
    {
        return new CachedFirebirdBuilder($query);
    }
}
