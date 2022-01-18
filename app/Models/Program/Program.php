<?php

namespace App\Models\Program;

use App\Models\Patient\Patient;
use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'programs';
    use HasFactory;
	protected $guarded = [];
    
    public function type()
    {
        return $this->hasOne(GlobalCode::class,'id','typeId');
    }

}
