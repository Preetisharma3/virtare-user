<?php

namespace App\Models\Inventory;

use App\Models\GlobalCode\GlobalCode;
use App\Models\Patient\PatientInventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'inventories';
    use HasFactory;
    protected $guarded = [];

    public function deviceTypes()
    {
        return $this->hasOne(GlobalCode::class, 'id', 'deviceType');
    }

    public function inventory(){
        return $this->hasOne(PatientInventory::class,'inventoryId','id');
    }
}
