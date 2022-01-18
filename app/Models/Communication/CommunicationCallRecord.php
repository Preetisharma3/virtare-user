<?php

namespace App\Models\Communication;

use App\Models\GlobalCode\GlobalCode;
use App\Models\Staff\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommunicationCallRecord extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'communicationCallRecords';
    use HasFactory;
	protected $guarded = [];

    public function staff()
    {
        return $this->hasOne(Staff::class,'id','staffId');
    }

    public function status(){
        return $this->belongsTo(GlobalCode::class,'callStatusId');
    }
}
