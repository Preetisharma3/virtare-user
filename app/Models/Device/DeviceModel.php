<?php

namespace App\Models\Device;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceModel extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'inventories';
    use HasFactory;
    protected $guarded = [];

    public function deviceType()
    {
        return $this->belongsTo(GlobalCode::class,'deviceTypeId');
    }

   
}
