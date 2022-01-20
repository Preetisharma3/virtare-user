<?php

namespace App\Models\Staff;

use App\Models\GlobalCode\GlobalCode;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;
	protected $table = 'staffs';
    use HasFactory;
	protected $guarded = [];

    public function network()
	{
		return $this->belongsTo(GlobalCode::class, 'networkId');
	}

    public function specialization()
	{
		return $this->belongsTo(GlobalCode::class, 'specializationId');
	}

	public function designation()
	{
		return $this->belongsTo(GlobalCode::class, 'designationId');
	}

	public function gender()
	{
		return $this->belongsTo(GlobalCode::class, 'genderId');
	}

	public function roles()
	{
		return $this->belongsTo(Role::class, 'roleId');
	}
}
