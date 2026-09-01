<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
        ])->validate();

        $task = Task::create([
            'id' => (string) Str::uuid(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'assignee_id' => $data['assignee_id'] ?? null,
            'created_by_id' => $request->user()->id,
        ]);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $data = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:todo,in_progress,done,cancelled',
            'assignee_id' => 'sometimes|nullable|exists:users,id',
        ])->validate();

        $task->update($data);

        return $task;
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['status' => 'deleted']);
    }
}
