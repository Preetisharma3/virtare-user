<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Task\Task;
use Illuminate\Support\Str;
use App\Models\Task\TaskCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Task\TaskAssignedTo;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Task\TaskTransformer;
use App\Transformers\Patient\PatientCountTransformer;

class TaskService
{

    public function addTask($request)
    {
        $startDate = Helper::date($request->input('startDate'));
        $dueDate = Helper::date($request->input('dueDate'));
        $input = [
            'udid' => Str::uuid()->toString(),
            'title' => $request->title,
            'description' => $request->description,
            'startDate' => $startDate,
            'dueDate' => $dueDate,
            'taskTypeId' => 69,
            'priorityId' => $request->priority,
            'taskStatusId' => $request->taskStatus,
            'createdBy' => Auth::user()->id,
        ];
        $task = Task::create($input);
        $taskCategoryId = $request->taskCategory;
        foreach ($taskCategoryId as $taskCategory) {
            $taskCate = [
                'taskId' => $task->id,
                'taskcategoryId' => $taskCategory,
            ];
            TaskCategory::create($taskCate);
        }
        $assignedToId = $request->assignedTo;
        foreach ($assignedToId as $assignedTo) {
            $assigne = Helper::entity($request->input('entity'), $assignedTo);
            $assigned = [
                'taskId' => $task->id,
                'assignedTo' => $assigne,
                'entityType' => $request->entityType
            ];
            TaskAssignedTo::create($assigned);
        }
        $taskData =  Task::where('id', $task->id)->with('assignedTo.assigned', 'assignedTo.patient')->first();
        $message = ['message' => trans('messages.created_succesfully')];
        $result = fractal()->item($taskData)->transformWith(new TaskTransformer())->toArray();
        $data = array_merge($message, $result);
        return $data;
    }

    public function listTask($request)
    {
        if ($request->latest) {
            $data = Task::with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->first();
            return fractal()->item($data)->transformWith(new TaskTransformer())->toArray();
        } else {
            $data = Task::with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->orderBy('createdAt', 'DESC')->get();
            return fractal()->collection($data)->transformWith(new TaskTransformer())->toArray();
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
        $message = ['message' => trans('messages.updated_succesfully')];
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
            Task::where('id', $id)->update($data);
            Task::where('id', $id)->delete();
            return response()->json(['message' => trans('messages.deleted_succesfully')]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()],  500);
        }
    }

    public function taskById($id)
    {
        $data = Task::where('id', $id)->first();
        return fractal()->item($data)->transformWith(new TaskTransformer())->toArray();
    }

    public function taskPerStaff()
    {
        $tasks = DB::select(
            'CALL taskPerStaff()',
        );
        return fractal()->item($tasks)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }

    public function taskPerCategory()
    {
        $tasks = DB::select(
            'CALL taskPerCategory()',
        );
        return fractal()->item($tasks)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
    }
}
