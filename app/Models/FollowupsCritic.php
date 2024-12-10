<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FollowupsCritic
 *
 * @property $id
 * @property $reception_id
 * @property $pet_status
 * @property $preasure
 * @property $temperature
 * @property $glycemia
 * @property $throwup
 * @property $throwup_detail
 * @property $defecate
 * @property $defecate_detail
 * @property $orino
 * @property $orino_detail
 * @property $eat
 * @property $eat_detail
 * @property $infusions
 * @property $infusions_detail
 * @property $terapeutic
 * @property $terapeutic_detail
 * @property $imaging
 * @property $imaging_detail
 * @property $pends
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
class FollowupsCritic extends Model
{
    use SoftDeletes;
    protected $table = "followups-critics";


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'pet_status', 'preasure', 'temperature', 'glycemia', 'throwup', 'throwup_detail', 'defecate', 'defecate_detail', 'orino', 'orino_detail', 'eat', 'eat_detail', 'infusions', 'infusions_detail', 'terapeutic', 'terapeutic_detail', 'imaging', 'imaging_detail', 'pends', 'vet_id'];


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
