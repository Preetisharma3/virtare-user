<?php

namespace App\Models\Patient;

use App\Models\Flag\Flag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientFlag extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'patientFlags';
    use HasFactory;
	protected $guarded = [];

   public function flags(){
    return $this->belongsTo(Flag::class, 'flagId');
   }
}
