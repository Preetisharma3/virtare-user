<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessRole extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
    protected $table = 'accessRoles';
    use HasFactory;
    protected $guarded = [];
}
