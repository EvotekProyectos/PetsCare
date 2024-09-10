<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Schedule
 *
 * @property $id
 * @property $begin
 * @property $end
 * @property $shift_id
 * @property $user_id
 * @property $cover_area_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property CoverArea $coverArea
 * @property Shift $shift
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Schedule extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['begin', 'end', 'shift_id', 'user_id', 'cover_area_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coverArea()
    {
        return $this->belongsTo(\App\Models\CoverArea::class, 'cover_area_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift::class, 'shift_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    

}
