<?php

namespace App\Models\Patient;

use App\Models\Patient\Patient;
use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientReferal extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'patientReferals';
    use HasFactory;
	protected $guarded = [];
    

    public function designation()
    {
        return $this->hasOne(GlobalCode::class,'id','designationId');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class,'id');
    }
}
