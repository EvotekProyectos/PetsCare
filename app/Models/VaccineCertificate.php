<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VaccineCertificate
 *
 * @property $id
 * @property $pet_id
 * @property $service_id
 * @property $vaccine
 * @property $lab
 * @property $lote
 * @property $application_date
 * @property $next_vaccination_date
 * @property $observations_vaccine
 * @property $product_internal
 * @property $dose_internal
 * @property $last_deworming_internal
 * @property $next_internal_date
 * @property $observations_internal
 * @property $product_external
 * @property $dose_external
 * @property $last_deworming_external
 * @property $next_external_date
 * @property $observations_external
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Pet $pet
 * @property Service $service
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class VaccineCertificate extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pet_id', 'service_id', 'product', 'lab', 'lote', 'dose', 'application_date', 'last_deworming_date', 'next_application_date', 'observations', 'vet_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pet()
    {
        return $this->belongsTo(\App\Models\Pet::class, 'pet_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'service_id', 'id');
    }

    public function vet()
     {
         return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
     }

     public function microsip()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'product', 'ARTICULO_ID');
    }
    

}
