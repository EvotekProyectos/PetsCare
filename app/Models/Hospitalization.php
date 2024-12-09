<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hospitalization
 *
 * @property $id
 * @property $reception_id
 * @property $reason
 * @property $total_days
 * @property $total_payment
 * @property $already_paid
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Reception $reception
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Hospitalization extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'reason', 'total_days', 'total_payment', 'already_paid', 'hospital_discharges_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    
    public function hospitalDischarges()
    {
        return $this->belongsTo(\App\Models\HospitalDischarge::class, 'hospital_discharges_id', 'id');
    }
    

}
