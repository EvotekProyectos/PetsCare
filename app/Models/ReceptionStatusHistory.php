<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReceptionStatusHistory
 *
 * @property $id
 * @property $reception_id
 * @property $attention_status_id
 * @property $created_at
 * @property $updated_at
 *
 * @property AttentionStatus $attentionStatus
 * @property Reception $reception
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ReceptionStatusHistory extends Model
{
    use HasFactory;
    

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id', 'attention_status_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attentionStatus()
    {
        return $this->belongsTo(\App\Models\AttentionStatus::class, 'attention_status_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reception()
    {
        return $this->belongsTo(\App\Models\Reception::class, 'reception_id', 'id');
    }
    

}
