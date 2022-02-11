<?php

namespace App\Models\Provider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
	protected $table = 'providers';
    use HasFactory;
	protected $guarded = [];
}
