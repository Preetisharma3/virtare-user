<?php

namespace App\Models\Inventory;

use App\Models\GlobalCode\GlobalCode;
use App\Models\Patient\PatientInventory;
use App\Models\Device\DeviceModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
    protected $table = 'inventories';
    use HasFactory;
    protected $guarded = [];

    public function model()
    {
        return $this->hasOne(DeviceModel::class, 'id', 'deviceModelId');
    }

    public function inventory(){
        return $this->hasOne(PatientInventory::class,'inventoryId','id');
    }
}
