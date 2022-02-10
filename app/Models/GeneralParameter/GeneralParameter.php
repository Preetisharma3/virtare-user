<?php

namespace App\Models\GeneralParameter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralParameter extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'generalParameters';
    use HasFactory;
	protected $guarded = [];

    public function generalParameterGroup()
    {
        return $this->hasOne(GeneralParameterGroup::class,'id','generalParameterGroupId');
    }
}