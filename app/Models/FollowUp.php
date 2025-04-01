<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FollowUp
 *
 * @property $id
 * @property $reception_id
 * @property $time
 * @property $details
 * @property $temperature
 * @property $systolic
 * @property $diastolic
 * @property $average
 * @property $glycemia_level
 * @property $vet_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Reception $reception
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class FollowUp extends Model
{
    use SoftDeletes, HasFactory;


    protected $perPage = 20;
    protected $table = "follow-ups";

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'time', 'details', 'temperature', 'systolic', 'diastolic', 'average', 'glycemia_level', 'vet_id'];


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
    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
    }
    

}
