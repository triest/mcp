<?php

namespace App\Services;

use App\Models\Task;

class McpToolRegistry
{
    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_tasks',
                'description' => 'List tasks, optionally filtered by status (todo, in_progress, done, cancelled).',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'status' => ['type' => 'string', 'enum' => ['todo', 'in_progress', 'done', 'cancelled']],
                    ],
                ],
            ],
            [
                'name' => 'create_task',
                'description' => 'Create a new task.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'assignee_id' => ['type' => 'integer'],
                    ],
                    'required' => ['title'],
                ],
            ],
            [
                'name' => 'get_task',
                'description' => 'Get a single task by id.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => ['task_id' => ['type' => 'string']],
                    'required' => ['task_id'],
                ],
            ],
            [
                'name' => 'update_task_status',
                'description' => 'Update a task status: todo, in_progress, done, or cancelled.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'task_id' => ['type' => 'string'],
                        'status' => ['type' => 'string', 'enum' => ['todo', 'in_progress', 'done', 'cancelled']],
                    ],
                    'required' => ['task_id', 'status'],
                ],
            ],
            [
                'name' => 'assign_task',
                'description' => 'Assign a task to a user.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'task_id' => ['type' => 'string'],
                        'assignee_id' => ['type' => 'integer'],
                    ],
                    'required' => ['task_id', 'assignee_id'],
                ],
            ],
            [
                'name' => 'delete_task',
                'description' => 'Delete a task.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => ['task_id' => ['type' => 'string']],
                    'required' => ['task_id'],
                ],
            ],
        ];
    }

    public static function call(string $name, array $args, int $userId): array
    {
        return match ($name) {
            'list_tasks' => self::listTasks($args),
            'create_task' => self::createTask($args, $userId),
            'get_task' => self::getTask($args),
            'update_task_status' => self::updateTaskStatus($args),
            'assign_task' => self::assignTask($args),
            'delete_task' => self::deleteTask($args),
            default => throw new \InvalidArgumentException("Unknown tool: {$name}"),
        };
    }

    private static function listTasks(array $args): array
    {
        $query = Task::query();
        if (! empty($args['status'])) {
            $query->where('status', $args['status']);
        }

        return $query->orderByDesc('created_at')->get()->toArray();
    }

    private static function createTask(array $args, int $userId): array
    {
        $task = Task::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'title' => $args['title'],
            'description' => $args['description'] ?? null,
            'assignee_id' => $args['assignee_id'] ?? null,
            'created_by_id' => $userId,
        ]);

        return $task->toArray();
    }

    private static function getTask(array $args): array
    {
        return Task::findOrFail($args['task_id'])->toArray();
    }

    private static function updateTaskStatus(array $args): array
    {
        $task = Task::findOrFail($args['task_id']);
        $task->update(['status' => $args['status']]);

        return $task->toArray();
    }

    private static function assignTask(array $args): array
    {
        $task = Task::findOrFail($args['task_id']);
        $task->update(['assignee_id' => $args['assignee_id']]);

        return $task->toArray();
    }

    private static function deleteTask(array $args): array
    {
        $task = Task::findOrFail($args['task_id']);
        $task->delete();

        return ['status' => 'deleted', 'task_id' => $args['task_id']];
    }
}
