<?php

namespace App\Models\Patient;

use App\Models\Flag\Flag;
use App\Models\Patient\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientFlag extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
    protected $table = 'patientFlags';
    use HasFactory;
    protected $guarded = [];

    public function flag()
    {
        return $this->belongsTo(Flag::class, 'flagId');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'id');
    }
}
