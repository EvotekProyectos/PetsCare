<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Appointment
 *
 * @property $id
 * @property $reception_id
 * @property $anamnesis
 * @property $exam_details
 * @property $diagnosis
 * @property $observations
 * @property $day_next_check
 * @property $time_next_check
 * @property $reason_next_check_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Reason $reason
 * @property Reception $reception
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Appointment extends Model
{
    use SoftDeletes, HasFactory;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'anamnesis', 'exam_details', 'diagnosis', 'observations', 'day_next_check', 'time_next_check', 'reason_next_check_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reason()
    {
        return $this->belongsTo(\App\Models\Reason::class, 'reason_next_check_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    

}
