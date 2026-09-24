<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemVersion
 *
 * Contador de versión por clave (hoy solo 'receptions', ver migración
 * create_system_versions_table). Sin lógica propia -el incremento atómico
 * vive en ReceptionVersionService, no aquí-.
 *
 * @property $id
 * @property $key
 * @property $version
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SystemVersion extends Model
{
    protected $fillable = ['key', 'version'];
}
