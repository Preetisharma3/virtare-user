<?php

namespace App\Models\Patient;

use App\Models\Note\Note;
use App\Models\User\User;
use App\Models\Vital\VitalField;
use Illuminate\Support\Facades\DB;
use App\Models\Patient\PatientFlag;
use App\Models\Patient\PatientStaff;
use App\Models\Patient\PatientVital;
use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient\PatientCondition;
use App\Models\Patient\PatientInventory;
use App\Models\Patient\PatientFamilyMember;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Patient\PatientEmergencyContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
    protected $table = 'patients';
    use HasFactory;
    protected $guarded = [];


    // public function initials(): string
	// {
	// 	return substr($this->firstName, 0, 1);
	// }


    public function gender()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'genderId');
    }

    public function language()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'languageId');
    }

    public function otherLanguage()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'otherLanguageId');
    }

    public function contactType()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'contactTypeId');
    }

    public function contactTime()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'contactTimeId');
    }

    public function state()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'stateId');
    }
    

    public function country()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'countryId');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }

    public function family()
    {
        return $this->belongsTo(PatientFamilyMember::class, 'id');
    }

    public function emergency()
    {
        return $this->hasOne(PatientEmergencyContact::class, 'patientId');
    }

    public function vitals()
	{
        $patentId = $this->id;
		return $this->hasMany(PatientVital::class, 'patientId')->whereIn(DB::raw('(patientVitals.takeTime,vitalFieldId)'), function ($query) use($patentId) {
            return $query->from('patientVitals')
                ->selectRaw('max(`takeTime`),vitalFieldId')
                ->where('patientId',$patentId)
                ->groupBy("vitalFieldId");
                
        })->groupBy('vitalFieldId');
	}

    public function conditions()
	{
		return $this->belongsTo(PatientCondition::class, 'id','patientId');
	}

    public function flags()
	{
		return $this->hasMany(PatientFlag::class, 'patientId');
	}

    public function inventories()
	{
		return $this->hasMany(PatientInventory::class, 'patientId');
	}

    public function patientStaff()
	{
		return $this->belongsTo(PatientStaff::class, 'id','patientId');
	}

    public function vital()
	{
		return $this->hasMany(PatientVital::class, 'patientId')->whereRaw('id IN (select MAX(id) FROM patientVitals GROUP BY vitalFieldId)')
        ->orderBy('createdAt','desc');
	}

    public function notes()
   {
       return $this->hasMany(Note::class,'referenceId');
   }


}
