<?php

namespace App\Models\Communication;

use App\Models\Staff\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CallRecord extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
	protected $table = 'callRecords';
    use HasFactory;
	protected $guarded = [];

    public function communicationCallRecord()
    {
        return $this->hasOne(CommunicationCallRecord::class,'id');
    }
    public function staff()
    {
        return $this->hasOne(Staff::class,'id','staffId');
    }

}
