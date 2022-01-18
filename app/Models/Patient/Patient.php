<?php

namespace App\Models\Patient;

use App\Models\User\User;
use App\Models\Patient\PatientVital;
use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient\PatientFamilyMember;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Patient\PatientEmergencyContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'patients';
    use HasFactory;
    protected $guarded = [];

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
        return $this->hasOne(PatientFamilyMember::class, 'patientId');
    }

    public function emergency()
    {
        return $this->hasOne(PatientEmergencyContact::class, 'patientId');
    }

    public function vitals()
	{
		return $this->belongsTo(PatientVital::class, 'id','patientId');
	}

    public function conditions()
	{
		return $this->belongsTo(PatientCondition::class, 'id','patientId');
	}



}
