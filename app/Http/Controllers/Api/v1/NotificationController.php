<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\NotificationService;

class NotificationController extends Controller
{
    public function notif(){
        return (new NotificationService)->notif();
    }
}
