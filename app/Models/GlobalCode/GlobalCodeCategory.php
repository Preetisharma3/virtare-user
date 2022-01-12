<?php

namespace App\Models\GlobalCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalCodeCategory extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'globalCodeCategories';
    use HasFactory;
	protected $guarded = [];

    

    public function globalCode()
    {
        return $this->hasMany(GlobalCode::class,'globalCodeCategoryId');
    }
}
