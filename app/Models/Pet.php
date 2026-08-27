<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Pet
 *
 * @property $id
 * @property $family_id
 * @property $name
 * @property $picture_id
 * @property $specie
 * @property $raza
 * @property $species_id
 * @property $breed_id
 * @property $gender_id
 * @property $birthday
 * @property $reproductive_status_id
 * @property $weight
 * @property $physic_descrip
 * @property $notes
 * @property $pet_classification_id
 * @property $deceased
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Family $family
 * @property Genre $genre
 * @property PetClassification $petClassification
 * @property File $file
 * @property ReproductiveStatus $reproductiveStatus
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pet extends Model
{
    use SoftDeletes;
    use HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['number_chip','family_id', 'name', 'picture_id', 'specie', 'raza', 'species_id', 'breed_id', 'gender_id', 'birthday', 'reproductive_status_id', 'weight', 'physic_descrip', 'notes', 'pet_classification_id', 'deceased'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo(\App\Models\Family::class, 'family_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function species()
    {
        return $this->belongsTo(\App\Models\Species::class, 'species_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function breed()
    {
        return $this->belongsTo(\App\Models\Breed::class, 'breed_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function genre()
    {
        return $this->belongsTo(\App\Models\Genre::class, 'gender_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function petClassification()
    {
        return $this->belongsTo(\App\Models\PetClassification::class, 'pet_classification_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(\App\Models\File::class, 'picture_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reproductiveStatus()
    {
        return $this->belongsTo(\App\Models\ReproductiveStatus::class, 'reproductive_status_id', 'id');
    }
    
    public function vaccineCertificates()
    {
        return $this->hasMany(VaccineCertificate::class, 'pet_id', 'id');
    }

   
        public function receptions()
        {
            return $this->hasMany(Reception::class);
        }
public function prescriptions()
{
    return $this->hasMany(Prescription::class);
}

public function episodes()
{
    return $this->hasMany(Episode::class, 'pet_id', 'id');
}

}
