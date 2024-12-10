<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FollowupSurgical
 *
 * @property $id
 * @property $reception_id
 * @property $time
 * @property $alterations
 * @property $which_alterations
 * @property $therapeutic
 * @property $which_therapeutic
 * @property $vomiting
 * @property $quantity_vomiting
 * @property $defecation
 * @property $quantity_defecation
 * @property $urine
 * @property $quantity_urine
 * @property $feeding
 * @property $type_feeding
 * @property $pendings
 * @property $cleaning
 * @property $clean_observations
 * @property $secretion
 * @property $secretion_observations
 * @property $drainage
 * @property $quantity_drainage
 * @property $blockedages
 * @property $type_blocked
 * @property $infusions
 * @property $type_time_infusions
 * @property $alterations_surgery
 * @property $which_alterations_surgery
 * @property $observations
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Reception $reception
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class FollowupSurgical extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'date', 'alterations', 'which_alterations', 'therapeutic', 'which_therapeutic', 'vomiting', 'quantity_vomiting', 'defecation', 'quantity_defecation', 'urine', 'quantity_urine', 'feeding', 'type_feeding', 'pendings', 'cleaning', 'clean_observations', 'secretion', 'secretion_observations', 'drainage', 'quantity_drainage', 'blockedages', 'type_blocked', 'infusions', 'type_time_infusions', 'alterations_surgery', 'which_alterations_surgery', 'observations', 'vet_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    
    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id', 'id');
    }

}
