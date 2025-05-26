<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;


#[Post(
    path: '/api/tasks',
    summary: 'Create Task',
    security: [['sanctum' => []]],
    requestBody: new RequestBody(
        description: 'Task request',
        content:
        new JsonContent(
            ref: '#/components/schemas/TaskRequest',
        )
    ),
    tags: ['Tasks'],
    responses: [
        new Response(
            response: 201,
            description: 'Success',
            content: new JsonContent(
                ref: '#/components/schemas/TaskResource',
            )
        ),
        new Response(
            response: 422,
            description: 'Error: Unprocessable Entity',
        ),
        new Response(
            response: 401,
            description: 'Error: Error: Unauthorized',
        )
    ],
)]
class NewController extends Controller
{

    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
    ) {}

    public function new(TaskRequest $request): JsonResponse
    {
        $currentUserId = Auth::user()->getAuthIdentifier();

        if (!$currentUserId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validated();

        $task = $this->taskRepository->create($data, $currentUserId);

        return response()->json(new TaskResource($task), 201);
    }
}
