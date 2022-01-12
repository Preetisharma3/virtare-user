<?php

namespace App\Models\Communication;

use App\Models\Staff\Staff;
use App\Models\Patient\Patient;
use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Communication\CommunicationMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Communication extends Model
{
   
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'communications';
    use HasFactory;
	protected $guarded = [];


    public function communicationMessage()
    {
        return $this->belongsTo(CommunicationMessage::class, 'id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class,'createdBy','id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class,'id','patientId');
    }

    public function globalCode()
    {
        return $this->hasOne(GlobalCode::class,'id','messageCategoryId');
    }
    

}
