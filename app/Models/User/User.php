<?php

namespace App\Models\User;

use Carbon\Carbon;
use App\Models\Role\Role;
use App\Models\Staff\Staff;
use App\Models\Patient\Patient;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
       
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function initials(): string
	{
		return substr($this->firstName, 0, 1);
	}

    public function roles()
	{
		return $this->belongsTo(Role::class, 'roleId');
	}

    public function staff()
	{
		return $this->belongsTo(Staff::class,'id','userId');
	}

    public function patient()
	{
		return $this->belongsTo(Patient::class,'id','userId');
	}

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAgeAttribute($dateOfBirth)
	{
		return Carbon::parse($dateOfBirth)->age;
	}

}
