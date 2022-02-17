<?php

namespace App\Models\GeneralParameter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralParameterGroup extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
	protected $table = 'generalParameterGroups';
    use HasFactory;
	protected $guarded = [];

    public function generalParameter()
    {
        return $this->hasMany(GeneralParameter::class,'generalParameterGroupId');
    }
    
}
