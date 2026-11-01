<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    public function __construct(private readonly TaskRepository $taskRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/tasks',
        operationId: 'listTasks',
        description: 'Search matches task titles. Pagination is enabled by page or per_page; per_page defaults to 15 when either is provided.',
        summary: 'List tasks',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'search',
                description: 'Filter tasks by title.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', nullable: true, maxLength: 255),
                example: 'groceries'
            ),
            new OA\Parameter(
                name: 'sort',
                description: 'Field used to sort the tasks.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    default: 'created_at',
                    enum: ['created_at', 'due_date']
                )
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Page number when pagination is enabled.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', minimum: 1, default: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Tasks per page (maximum 100).',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, default: 15)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tasks returned successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Task')
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 422, description: 'The query parameters are invalid.'),
        ]
    )]
    public function index(ListTasksRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $paginationRequested = isset($filters['page']) || isset($filters['per_page']);
        $perPage = $paginationRequested ? ($filters['per_page'] ?? 15) : null;

        $tasks = $this->taskRepository->getTasks(
            $filters['search'] ?? null,
            $filters['sort'] ?? 'created_at',
            $perPage,
            $filters['page'] ?? 1
        );

        return response()->json([
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/tasks',
        operationId: 'createTask',
        summary: 'Create a task',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskInput')
        ),
        tags: ['Tasks'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task created successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'message', type: 'string', example: 'Task created successfully'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 422, description: 'The request body is invalid.'),
        ]
    )]
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskRepository->storeTask($request->validated());

        return response()->json([
            'id' => $task->getKey(),
            'message' => 'Task created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/tasks/{task}',
        operationId: 'getTask',
        summary: 'Get a task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                description: 'Task ID.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task returned successfully.',
                content: new OA\JsonContent(ref: '#/components/schemas/Task')
            ),
            new OA\Response(response: 404, description: 'Task not found.'),
        ]
    )]
    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/tasks/{task}',
        operationId: 'updateTask',
        description: 'Only the provided fields are updated.',
        summary: 'Update a task',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskUpdateInput')
        ),
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                description: 'Task ID.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task updated successfully.',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'Task updated successfully')],
                    type: 'object'
                )
            ),
            new OA\Response(response: 404, description: 'Task not found.'),
            new OA\Response(response: 422, description: 'The request body is invalid.'),
        ]
    )]
    #[OA\Patch(
        path: '/api/tasks/{task}',
        operationId: 'patchTask',
        description: 'Only the provided fields are updated.',
        summary: 'Update a task',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskUpdateInput')
        ),
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                description: 'Task ID.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task updated successfully.',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'Task updated successfully')],
                    type: 'object'
                )
            ),
            new OA\Response(response: 404, description: 'Task not found.'),
            new OA\Response(response: 422, description: 'The request body is invalid.'),
        ]
    )]
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->taskRepository->updateTaskById($task->getKey(), $request->validated());

        return response()->json([
            'message' => 'Task updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/api/tasks/{task}',
        operationId: 'deleteTask',
        summary: 'Delete a task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                description: 'Task ID.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task deleted successfully.',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'message', type: 'string', example: 'Task deleted successfully')],
                    type: 'object'
                )
            ),
            new OA\Response(response: 404, description: 'Task not found.'),
        ]
    )]
    public function destroy(Task $task): JsonResponse
    {
        $this->taskRepository->deleteById($task->getKey());

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
