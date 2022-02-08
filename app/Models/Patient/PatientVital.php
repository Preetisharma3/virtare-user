<?php

namespace App\Models\Patient;

use App\Models\GlobalCode\GlobalCode;
use App\Models\Vital\VitalField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientVital extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'patientVitals';
    use HasFactory;
	protected $guarded = [];
    
   
    public function vitalFieldNames()
    {
        return $this->hasOne(VitalField::class,'id','vitalFieldId');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patientId');
    }
}
