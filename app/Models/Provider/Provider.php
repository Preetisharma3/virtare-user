<?php

namespace App\Models\Provider;

use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
    protected $table = 'providers';
    use HasFactory;
    protected $guarded = [];


    public function country(){
        return $this->belongsTo(GlobalCode::class,'countryId');
    }

    public function state(){
        return $this->belongsTo(GlobalCode::class,'stateId');
    }
}
