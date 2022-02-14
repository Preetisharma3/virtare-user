<?php

namespace App\Models\Patient;
;

use App\Models\GlobalCode\GlobalCode;
use App\Models\Staff\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientTimeLog extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
	protected $table = 'patientTimeLogs';
    use HasFactory;
	protected $guarded = [];
    
   public function category()
   {
       return $this->hasOne(GlobalCode::class,'id','categoryId');
   }

   public function logged()
   {
       return $this->hasOne(Staff::class,'id','loggedId');
   }

   public function performed()
   {
       return $this->hasOne(Staff::class,'id','loggedId');
   }
    
}