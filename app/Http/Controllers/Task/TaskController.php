<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;


#[Get(
    path: '/api/tasks/{id}',
    description: 'Task information',
    summary: 'Get information of task',
    security: [['sanctum' => []]],
    tags: ['Tasks'],
    parameters: [
        new Parameter(
            name: 'id',
            description: 'Task Id',
            in: 'path',
            required: true,
            example: 1
        )
    ],
    responses: [
        new Response(
            response: 200,
            description: 'Success',
            content: new JsonContent(
                ref: '#/components/schemas/TaskResource',
            )
        ),
        new Response(
            response: 401,
            description: 'Error: Unauthorized',
        ),
    ]
)]
class TaskController extends Controller
{

    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
    ) {}
    public function current(int $id): JsonResponse
    {
        $currentUserId = Auth::user()->getAuthIdentifier();

        if (!$currentUserId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $task = $this->taskRepository->findById($id, $currentUserId);

        if(is_null($task)) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json(new TaskResource($task));
    }
}
