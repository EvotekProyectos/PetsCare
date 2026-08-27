<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Species
 *
 * Catálogo de especies (Perro, Gato, ...). 1:N con Breed — las razas de cada
 * especie se agregan como registros nuevos en breeds, sin tocar esta tabla
 * ni el código.
 *
 * @property $id
 * @property $name
 * @property $icon
 * @property $active
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Species extends Model
{
    use HasFactory;

    protected $table = 'species';

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function breeds()
    {
        return $this->hasMany(\App\Models\Breed::class, 'species_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pets()
    {
        return $this->hasMany(\App\Models\Pet::class, 'species_id', 'id');
    }
}
