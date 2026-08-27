<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Breed
 *
 * Catálogo de razas, agrupadas por especie (species_id). Por ahora solo hay
 * razas cargadas para Perro (ver BreedSeeder); agregar razas de otras
 * especies es solo insertar registros nuevos, no requiere cambios de
 * estructura.
 *
 * @property $id
 * @property $species_id
 * @property $name
 * @property $active
 * @property $created_at
 * @property $updated_at
 *
 * @property Species $species
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Breed extends Model
{
    use HasFactory;

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['species_id', 'name', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function species()
    {
        return $this->belongsTo(\App\Models\Species::class, 'species_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pets()
    {
        return $this->hasMany(\App\Models\Pet::class, 'breed_id', 'id');
    }
}
