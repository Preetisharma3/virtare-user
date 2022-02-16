<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{

    use SoftDeletes;
    protected $softDelete = true;
    const DELETED_AT = 'deletedAt';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;
    protected $table = 'notifications';
    use HasFactory;
    protected $guarded = [];

  

    public function created_user(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
