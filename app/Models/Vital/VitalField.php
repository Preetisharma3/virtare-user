<?php

namespace App\Models\Vital;

use App\Models\Patient\Patient;
use App\Models\Patient\PatientVital;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VitalField extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'vitalFields';
    use HasFactory;
	protected $guarded = [];
    
    public function patient()
    {
        return $this->belongsTo(Patient::class,'id');
    }
    public function vital()
    {
        return $this->belongsTo(PatientVital::class,'id','typeId');
    }


}
