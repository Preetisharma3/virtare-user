<?php

namespace App\Services\Api;

use App\Models\Task\Task;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Task\TaskTransformer;
use App\Transformers\Task\TaskStatusTransformer;
use App\Transformers\Task\TaskPriorityTransformer;

class TaskService
{
    
    public function addTask($request){
        $input = [
            'udid'=>Str::uuid()->toString(),
            'title'=>$request->title,
            'description'=>$request->description,
            'startDate'=>date("Y-m-d H:i:s",$request->startDate),
            'dueDate'=>date("Y-m-d H:i:s",$request->dueDate),
            'taskCategoryId' =>json_encode($request->taskCategory),
            'taskTypeId' =>69,
            'priorityId' =>$request->priority,
            'taskStatusId' =>$request->taskStatus,
            'assignedTo' =>json_encode($request->assignedTo),
            'entityType'=>$request->entityType,
            'createdBy'=>Auth::user()->id,
        ];
        Task::create($input);
        return response()->json(['message'=>'Created Successfully']);
    }

    public function listTask(){
        $data = Task::with('taskCategory','taskType','priority','taskStatus','staff','user')->get();
       return fractal()->collection($data)->transformWith(new TaskTransformer())->toArray();
    }

    // Task List According to priorities
    public function priorityTask($request){
        $data =Task::select('priorityId', DB::raw('count(*) as count'))->groupBy('priorityId')->get();
        return fractal()->collection($data)->transformWith(new TaskPriorityTransformer())->toArray();
    }

    // Task List According to statuses
    public function statusTask($request){
        $data =Task::select('taskStatusId', DB::raw('count(*) as count'))->groupBy('taskStatusId')->get();
        return fractal()->collection($data)->transformWith(new TaskStatusTransformer())->toArray();
    }

    
}
