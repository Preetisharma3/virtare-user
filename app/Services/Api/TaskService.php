<?php

namespace App\Services\Api;

use Exception;
use App\Helper;
use App\Models\Task\Task;
use Illuminate\Support\Str;
use App\Models\Task\TaskCategory;
use App\Library\ErrorLogGenerator;
use Illuminate\Support\Facades\DB;
use App\Models\Task\TaskAssignedTo;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Task\TaskTransformer;
use App\Transformers\Patient\PatientCountTransformer;
use App\Transformers\Task\TaskDurationCountTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TaskService
{

    public function addTask($request)
    {
        try {
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
                $assign = Helper::entity($request->entityType, $assignedTo);
                $assigned = [
                    'taskId' => $task->id,
                    'assignedTo' => $assign,
                    'entityType' => $request->entityType
                ];
                TaskAssignedTo::create($assigned);
            }

            $taskData =  Task::where('id', $task->id)->with('assignedTo.assigned', 'assignedTo.patient')->first();
            $message = ['message' => trans('messages.createdSuccesfully')];
            $result = fractal()->item($taskData)->transformWith(new TaskTransformer())->toArray();
            $data = array_merge($message, $result);
            return $data;
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function listTask($request)
    {
        try {
            if ($request->all) {
                if (auth()->user()->roleId == 3) {
                    $data = Task::whereHas('assignedTo', function ($query) {
                        $query->where('assignedTo', auth()->user()->staff->id);
                    })->where('title', 'LIKE', '%' . $request->search . '%')->with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->get();
                } else {
                    $data = Task::where('title', 'LIKE', '%' . $request->search . '%')->with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->get();
                }
                return fractal()->collection($data)->transformWith(new TaskTransformer())->toArray();
            } else {
                if (auth()->user()->roleId == 3) {
                    $data = Task::whereHas('assignedTo', function ($query) {
                        $query->where([['assignedTo', auth()->user()->staff->id], ['entityType', 'staff']]);
                    })->where('title', 'LIKE', '%' . $request->search . '%')->with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->paginate(env('PER_PAGE', 20));
                } else {
                    $data = Task::where('title', 'LIKE', '%' . $request->search . '%')->with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->paginate(env('PER_PAGE', 20));
                }
                return fractal()->collection($data)->transformWith(new TaskTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function entityTaskList($request, $entity, $id)
    {
        try {
            if ($request->all) {
                $reference = Helper::entity($entity, $id);
                $data = Task::whereHas('assignedTo', function ($query) use ($entity, $reference) {
                    $query->where([['entityType', $entity], ['assignedTo', $reference]]);
                })->with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->get();
                return fractal()->collection($data)->transformWith(new TaskTransformer())->toArray();
            } else {
                $reference = Helper::entity($entity, $id);
                $data = Task::whereHas('assignedTo', function ($query) use ($entity, $reference) {
                    $query->where([['entityType', $entity], ['assignedTo', $reference]]);
                })->with('taskCategory', 'taskType', 'priority', 'taskStatus', 'user')->latest()->paginate(env('PER_PAGE', 20));
                return fractal()->collection($data)->transformWith(new TaskTransformer())->paginateWith(new IlluminatePaginatorAdapter($data))->toArray();
            }
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Task List According to priorities
    public function priorityTask($request)
    {
        try {
            $data = DB::select(
                'CALL taskPriorityCount()',
            );
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // Task List According to statuses
    public function statusTask($request)
    {
        try {
            $tasks = DB::select(
                'CALL taskStatusCount()',
            );
            $total = DB::select(
                'CALL totalTasksCount()'
            );
            $data = array_merge($tasks, $total);
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    // get task with duration,time for 24hurs
    public function taskTotalWithTimeDuration($request)
    {
        try {
            $timelineId =  $request->timelineId;
            $data = DB::select(
                'CALL getTotalTaskSummaryCountInGraph()',
            );
            return fractal()->collection($data)->transformWith(new TaskDurationCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function taskCompletedRates($request)
    {
        try {
            $timelineId =  $request->timelineId;
            $data = DB::select(
                'CALL taskCompletedRates()',
            );
            if (isset($data[0])) {
                $data = $data[0];
            }
            return fractal()->item($data)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function updateTask($request, $id)
    {
        try {
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
            $message = ['message' => trans('messages.updatedSuccesfully')];
            $result =  fractal()->item($updatedData)->transformWith(new TaskTransformer())->toArray();
            $endData = array_merge(
                $message,
                $result
            );
            return $endData;
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function deleteTask($request, $id)
    {
        try {
            $data = ['deletedBy' => Auth::id(), 'isDelete' => 1, 'isActive' => 0];
            Task::where('id', $id)->update($data);
            Task::where('id', $id)->delete();
            return response()->json(['message' => trans('messages.deletedSuccesfully')]);
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function taskById($request, $id)
    {
        try {
            $data = Task::where('id', $id)->first();
            return fractal()->item($data)->transformWith(new TaskTransformer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function taskPerStaff($request)
    {
        try {
            $tasks = DB::select(
                'CALL taskPerStaff()',
            );
            return fractal()->item($tasks)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }

    public function taskPerCategory($request)
    {
        try {
            $tasks = DB::select(
                'CALL taskPerCategory()',
            );
            return fractal()->item($tasks)->transformWith(new PatientCountTransformer())->serializeWith(new \Spatie\Fractalistic\ArraySerializer())->toArray();
        } catch (Exception $e) {
            if (isset(auth()->user()->id)) {
                $userId = auth()->user()->id;
            } else {
                $userId = "";
            }

            ErrorLogGenerator::createLog($request, $e, $userId);
            $response = ['message' => $e->getMessage()];
            return response()->json($response,  500);
        }
    }
}
