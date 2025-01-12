<?php

namespace App\Models\Staff;

use Carbon\Carbon;
use App\Models\Role\Role;
use App\Models\GlobalCode\GlobalCode;
use App\Models\Appointment\Appointment;
use App\Models\Patient\PatientStaff;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
	use SoftDeletes;
	protected $softDelete = true;
	const DELETED_AT = 'deletedAt';
	const CREATED_AT = 'createdAt';
	const UPDATED_AT = 'updatedAt';
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

	public function appointment()
	{
		return $this->hasMany(Appointment::class, 'staffId');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}

	public function todayAppointment()
	{
		return $this->appointment()->where('startDate', Carbon::today());
	}

	public function patientStaff()
	{
		return $this->hasMany(PatientStaff::class, 'staffId');
	}
}
