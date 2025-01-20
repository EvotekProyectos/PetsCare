<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Reception
 *
 * @property $id
 * @property $reception_type_id
 * @property $admission_type_id
 * @property $area_id
 * @property $family_id
 * @property $pet_id
 * @property $reason_id
 * @property $veterinarian_id
 * @property $recepcionist_id
 * @property $room_id
 * @property $entry_date
 * @property $exit_date
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property AdmissionType $admissionType
 * @property Area $area
 * @property Family $family
 * @property Pet $pet
 * @property Reason $reason
 * @property User $user
 * @property ReceptionType $receptionType
 * @property Room $room
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Reception extends Model
{
    use SoftDeletes;


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reception_type_id', 'admission_type_id', 'area_id', 'family_id', 'pet_id', 'reason_id', 'veterinarian_id', 'recepcionist_id', 'room_id', 'entry_date', 'exit_date', 'num'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admissionType()
    {
        return $this->belongsTo(\App\Models\AdmissionType::class, 'admission_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(\App\Models\Area::class, 'area_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo(\App\Models\Family::class, 'family_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pet()
    {
        return $this->belongsTo(\App\Models\Pet::class, 'pet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reason()
    {
        return $this->belongsTo(\App\Models\Reason::class, 'reason_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receptionist()
    {
        return $this->belongsTo(\App\Models\User::class, 'recepcionist_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receptionType()
    {
        return $this->belongsTo(\App\Models\ReceptionType::class, 'reception_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(\App\Models\Room::class, 'room_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'veterinarian_id', 'id');
    }

    public function statusHistory()
    {
        return $this->hasMany(ReceptionStatusHistory::class, 'reception_id', 'id');
    }

    public function redSheet()
    {
        return $this->belongsTo(\App\Models\RedSheet::class, 'red_sheet_id', 'id');
    }

    public function surgery()
    {
        return $this->belongsTo(\App\Models\Surgery::class, 'surgery_id', 'id');
    }

    public function hospitalizations()
    {
        return $this->hasMany(Hospitalization::class, 'reception_id');
    }

    public function statusGrooming() {
        return $this->hasMany(GroomingStatusHistory::class, 'reception_id', 'id');
   }

   public function payment() {
        return $this->belongsTo(\App\Models\PaymentOrder::class, 'id', 'reception_id');
   }
}
