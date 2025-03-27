<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hotel
 *
 * @property $id
 * @property $reception_id
 * @property $vaccine_certificate_id
 * @property $food
 * @property $objects
 * @property $observations
 * @property $number_days
 * @property $service_type_id
 * @property $cubicle_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Cubicle $cubicle
 * @property Reception $reception
 * @property VaccineCertificate $vaccineCertificate
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Hotel extends Model
{
    use SoftDeletes, HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'food', 'objects', 'observations', 'number_days', 'extension','finish_date','video','status','service_type_id', 'cubicle_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cubicle()
    {
        return $this->belongsTo(\App\Models\Cubicle::class, 'cubicle_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function vaccineCertificate()
    // {
    //     return $this->belongsTo(\App\Models\VaccineCertificate::class, 'vaccine_certificate_id', 'id');
    // }
    
    public function servicie()
    {
        return $this->belongsTo(\App\Models\Precios::class, 'service_type_id', 'ARTICULO_ID');
    }

    public function serv()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'service_type_id', 'ARTICULO_ID');
    }

}
