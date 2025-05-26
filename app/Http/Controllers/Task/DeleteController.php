<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;


#[Delete(
    path: '/api/tasks/{id}',
    description: 'Deleting a task',
    summary: 'Deleting a task',
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
            response: 204,
            description: 'Success',
            content: null
        ),
        new Response(
            response: 404,
            description: 'Task not found',
        ),
        new Response(
            response: 500,
            description: 'Error: Internal Server Error',
        )
    ]
)]
class DeleteController extends Controller
{

    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
    ) {}

    public function delete(int $id): JsonResponse
    {
        if ($this->taskRepository->delete($id)) {
            return response()->json(null, 204);
        }
        return response()->json(['message' => 'Task not found'], 404);
    }
}
