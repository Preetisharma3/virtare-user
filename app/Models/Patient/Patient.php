<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'patients';
    use HasFactory;
	protected $guarded = [];
    

    public function globalCode()
    {
        return $this->hasMany(GlobalCode::class,'id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id');
    }
}
