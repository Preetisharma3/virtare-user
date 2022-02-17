<?php

namespace App\Models\Patient;

use App\Models\Patient\Patient;
use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientStaff extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
	protected $table = 'patientStaffs';
    use HasFactory;
	protected $guarded = [];
    
    public function patient()
    {
        return $this->hasMany(Patient::class,'id','patientId');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class,'id','staffId');
    }
}
