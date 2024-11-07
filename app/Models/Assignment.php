<?php

namespace App\Models;

use App\Observers\receptionStatusHistoryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_id'];


    public function reception()
    {
        return $this->belongsTo(\App\Models\User::class, 'recepcion_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(\App\Models\Room::class, 'room_id', 'id');
    }
    
    
}
