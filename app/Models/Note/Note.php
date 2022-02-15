<?php

namespace App\Models\Note;

use App\Models\User\User;
use App\Models\GlobalCode\GlobalCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const Created_AT = 'createdAt';
    const Updated_AT = 'updatedAt';
    public $timestamps = false;
    protected $table = 'notes';
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->hasOne(GlobalCode::class,'id','categoryId');
    }

    public function typeName()
    {
        return $this->hasOne(GlobalCode::class,'id','type');
    }

    public function user(){
        return $this->hasOne(User::class,'id','createdBy');
    }

}
