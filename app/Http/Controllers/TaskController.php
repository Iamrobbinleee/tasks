<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Exception;

class TaskController extends Controller
{
    public function getTasks(){
        try {
            $tasks = Task::whereNull('deleted_at')->get();
            if($tasks->isEmpty()){
                return response([
                    'message' => 'No Tasks Found.',
                ]);
            }

            return response([
                'tasks' => $tasks,
            ]);
        } catch (\Exception $e) {
            $statusCode = method_exists($e, 'getStatusCode') 
            ? $e->getStatusCode() 
            : ($e->getCode() ?: 500);

            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function getTask($id){
        try {
           $task = Task::where('id', $id)
           ->whereNull('deleted_at')
           ->first();
            if(!$task){
                return response([
                    'message' => 'Task not Found.',
                ]);
            }

            return response([
                'task' => $task,
            ]);
        } catch (\Exception $e) {
            $statusCode = method_exists($e, 'getStatusCode') 
            ? $e->getStatusCode() 
            : ($e->getCode() ?: 500);

            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function addTask(Request $request){
        try {

            $validate_task = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string',
                'due_date' => 'nullable|date',
                'priority' => 'required|in:low,medium,high',
                'is_completed' => 'boolean', 
            ]);

            $new_task = Task::create($validate_task);

            return response([
                'message' => 'New Task has been added successfully!',
                'data' => $new_task
            ], 201);

        } catch (\Exception $e) {
            $statusCode = method_exists($e, 'getStatusCode') 
            ? $e->getStatusCode() 
            : ($e->getCode() ?: 500);

            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function updateTask(Request $request, $id){
        try {

            $task = Task::findOrFail($id);
            if (!$task) {
                return response()->json(['message' => 'Task not found.'], 404);
            }

            $validate_task = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string',
                'due_date' => 'nullable|date',
                'priority' => 'required|in:low,medium,high',
                'is_completed' => 'boolean', 
            ]);

            $task->update($validate_task);

            return response([
                'message' => 'Task has been updated successfully!',
                'data' => $task
            ], 200);

        } catch (\Exception $e) {
            $statusCode = method_exists($e, 'getStatusCode') 
            ? $e->getStatusCode() 
            : ($e->getCode() ?: 500);

            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function deleteTask($id){
        try {

            $task = Task::findOrFail($id);
            if (!$task) {
                return response()->json(['message' => 'Task not found.'], 404);
            }
            
            $task->delete();

            return response()->json(['message' => 'Task has been deleted successfully.']);

        } catch (\Exception $e) {
            $statusCode = method_exists($e, 'getStatusCode') 
            ? $e->getStatusCode() 
            : ($e->getCode() ?: 500);

            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], $statusCode);
        }
    }
}
