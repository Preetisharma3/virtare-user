<?php

namespace App\Models\Permission;

use App\Models\Action\Action;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'permissions';
    use HasFactory;
    protected $guarded = [];
    

    public function role()
    {
        return $this->belongsTo(Role::class,'roleId');
    }

    public function actions()
    {
        return $this->belongsTo(Action::class,'actionId');
    }
}
