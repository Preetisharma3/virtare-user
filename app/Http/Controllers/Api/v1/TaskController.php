<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function addTask(request $request)
    {
        return (new TaskService)->addTask($request);
    }

    public function listTask(request $request)
    {
        return (new TaskService)->listTask($request);
    }

    public function priorityTask(request $request)
    {
        return (new TaskService)->priorityTask($request);
    }

    public function statusTask(request $request)
    {
        return (new TaskService)->statusTask($request);
    }

    public function updateTask(request $request, $id)
    {
        return (new TaskService)->updateTask($request, $id);
    }

    public function taskById($id){
        return (new TaskService)->taskById($id);
    }

    public function deleteTask($id)
    { 
        return (new TaskService)->deleteTask($id);
    }
}
