<?php

namespace App\Models\Patient;

use App\Models\Patient\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientMedicalHistory extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'patientMedicalHistories';
    use HasFactory;
	protected $guarded = [];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'id');
    }

    
}
