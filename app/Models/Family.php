<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Family
 *
 * @property $id
 * @property $name
 * @property $phone
 * @property $email
 * @property $address
 * @property $contact_name
 * @property $contact_number
 * @property $fam_classification_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property FamClassification $famClassification
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Family extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'phone', 'email', 'address', 'contact_name', 'contact_number', 'fam_classification_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function famClassification()
    {
        return $this->belongsTo(\App\Models\FamClassification::class, 'fam_classification_id', 'id');
    }
    
    public function pets()
    {
        return $this->hasMany(Pet::class, 'family_id', 'id');
    }

}
