<?php

namespace App\Models\GlobalCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalCode extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'globalCodes';
    use HasFactory;
	protected $guarded = [];

    public function globalCodeCategory()
    {
        return $this->hasOne(GlobalCodeCategory::class,'id','globalCodeCategoryId');
    }
}
