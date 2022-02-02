<?php

namespace App\Models\UserRole;

use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'userRoles';
    use HasFactory;
    protected $guarded = [];


    public function roles()
    {
        return $this->belongsTo(Role::class, 'roleId');
    }
}
