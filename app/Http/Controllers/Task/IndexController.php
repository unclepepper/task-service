<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;


#[Get(
    path: '/api/tasks',
    description: 'Get collection of tasks',
    summary: 'Get collection of tasks',
    security: [['sanctum' => []]],
    tags: ['Tasks'],
    parameters: [
        new Parameter(
            name: 'status',
            description: 'Task Id',
            in: 'query',
            required: false,
            example: 'in_progress'
        )
    ],
    responses: [
        new Response(
            response: 200,
            description: 'Success',
            content: new JsonContent(
                type: 'array',
                items: new Items(
                    ref: '#/components/schemas/TaskResource',
                )
            )
        ),
        new Response(
            response: 401,
            description: 'Error: Unauthorized',
        ),
    ]
)]
class IndexController extends Controller
{

    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
    ) {}

    public function index(): JsonResponse
    {
        $currentUserId = Auth::user()->getAuthIdentifier();

        if (!$currentUserId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $status = null;

        if (request()->has('status')) {
            $status = request()->status;
        }

        $tasks = $this->taskRepository->findAllByUserId($currentUserId, $status);



        return response()->json(TaskResource::collection($tasks));
    }
}
