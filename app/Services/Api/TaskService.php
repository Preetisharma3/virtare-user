<?php

namespace App\Services\Api;

use Exception;
use App\Models\Task\Task;
use App\Models\Staff\Staff;
use Illuminate\Support\Str;
use App\Models\Task\TaskCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Task\TaskAssignedTo;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Task\TaskTransformer;
use App\Transformers\Patient\PatientCountTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TaskService
{

    public function addTask($request)
    {
        $input = [
            'udid' => Str::uuid()->toString(),
            'title' => $request->title,
            'description' => $request->description,
            'startDate' => date("Y-m-d H:i:s", $request->startDate),
            'dueDate' => date("Y-m-d H:i:s", $request->dueDate),
            'taskTypeId' => 69,
            'priorityId' => $request->priority,
            'taskStatusId' => $request->taskStatus,
            'createdBy' => Auth::user()->id,
        ];
        $task = Task::create($input);
        $taskCategoryId = $request->taskCategory;
        foreach($taskCategoryId as $taskCategory){
            $taskCate = [
                'taskId' => $task->id,
                'taskcategoryId' => $taskCategory,
            ];
            TaskCategory::create($taskCate);
        }
        $assignedToId = $request->assignedTo;
        if($request->entityType=='staff'){
            foreach($assignedToId as $assignedTo){
            $staff=Staff::where('udid',$assignedTo)->first();
                $assigned = [
                    'taskId'=>$task->id,
                    'assignedTo'=>$staff->id,
                    'entityType'=>$request->entityType
                ];
                TaskAssignedTo::create($assigned);
            }
        }else{
            foreach($assignedToId as $assignedTo){
                $assigned = [
                    'taskId'=>$task->id,
                    'assignedTo'=>$assignedTo,
                    'entityType'=>$request->entityType
                ];
                TaskAssignedTo::create($assigned);
            }
        }
        $taskData =  Task::where('id',$task->id)->with('assignedTo.assigned','assignedTo.patient')->first();
       $message = ['message' => 'Created Successfully'];
       $result =fractal()->item($taskData)->transformWith(new TaskTransformer())->toArray();

       $data = array_merge($message,$result);
       return $data;
    }
         
    public function listTask($request)
    {
        if ($request->latest) {
            $data = Task::with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->first();
            return fractal()->item($data)->transformWith(new TaskTransformer())->toArray();
        } else {
            $data = Task::with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->paginate(5);
            return fractal()->collection($data)->transformWith(new TaskTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
        }

    }

    // Task List According to priorities
    public function priorityTask($request)
    {
        $data = DB::select(
            'CALL taskPriorityCount()',
        );
        return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }

    // Task List According to statuses
    public function statusTask($request)
    {

        $tasks = DB::select(

            'CALL taskStatusCount()',
        );
        $total = DB::select(
            'CALL totalTasksCount()'
        );
        $data = array_merge($tasks, $total);
        return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }


    public function updateTask($request, $id)
    {
        $input = [
            'title' => $request->title,
            'description' => $request->description,
            'taskStatusId' => $request->taskStatus,
            'priorityId' => $request->priority,
            'updatedBy' => auth()->user()->id
        ];
        Task::where('id', $id)->update(
            $input
        );
        $updatedData = Task::where('id', $id)->first();
        $message = ['message' => 'Updated Successfully'];
        $result =  fractal()->item($updatedData)->transformWith(new TaskTransformer())->toArray();
        $endData = array_merge(
            $message,
            $result
        );
        return $endData;
    }

    public function deleteTask($id)
    {
        try {
            $data = ['deletedBy' => 1, 'isDelete' => 1, 'isActive' => 0];
            Task::where('id',$id)->update($data);
            Task::where('id',$id)->delete();
            return response()->json(['message' => 'delete successfully']);
       } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
       }
    }

    public function taskById($id)
    {
        $data = Task::where('id', $id)->first();
        return fractal()->item($data)->transformWith(new TaskTransformer())->toArray();
    }

    public function taskPerStaff(){
        $tasks = DB::select(
            'CALL taskPerStaff()',
        );
        return fractal()->item($tasks)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }

    public function taskPerCategory(){
        $tasks = DB::select(
            'CALL taskPerCategory()',
        );
        return fractal()->item($tasks)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }

}
