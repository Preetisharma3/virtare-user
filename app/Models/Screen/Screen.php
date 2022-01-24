<?php

namespace App\Models\Screen;

use App\Models\Module\Module;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screen extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'screens';
    use HasFactory;
    protected $guarded = [];

    // public function module()
    // {
    //     return $this->belongsTo(Module::class,'moduleId');
    // }
}
