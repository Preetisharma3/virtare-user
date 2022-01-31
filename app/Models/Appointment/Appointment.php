<?php

namespace App\Models\Appointment;

use App\Models\GlobalCode\GlobalCode;
use App\Models\Patient\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Staff\Staff;
use App\Transformers\Staff\StaffAvailabilityTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'appointments';
    use HasFactory;
    protected $guarded = [];


    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientId');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staffId');
    }

    public function availability(){
        return $this->hasMany(StaffAvailability::class,'staffId');
    }

    public function appointmentType()
    {
        return $this->belongsTo(GlobalCode::class, 'appointmentTypeId');
    }

    public function duration()
    {
        return $this->belongsTo(GlobalCode::class, 'durationId');
    }
}
