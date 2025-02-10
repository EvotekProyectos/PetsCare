<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class GroomingStatusHistory
 *
 * @property $id
 * @property $reception_id
 * @property $grooming_status_id
 * @property $created_at
 * @property $updated_at
 *
 * @property GroomingStatus $groomingStatus
 * @property Reception $reception
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class GroomingStatusHistory extends Model
{
    use Notifiable;
    

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'grooming_status_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groomingStatus()
    {
        return $this->belongsTo(\App\Models\GroomingStatus::class, 'grooming_status_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    

}
