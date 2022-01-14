<?php

namespace App\Models\Patient;

use App\Models\User\User;
use App\Models\Patient\Patient;
use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientFamilyMember extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'patientFamilyMembers';
    use HasFactory;
    protected $guarded = [];


    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'id');
    }

    public function gender()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'genderId');
    }

    public function contactType()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'contactTypeId');
    }

    public function contactTime()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'contactTimeId');
    }

    public function relation()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'relationId');
    }
}
