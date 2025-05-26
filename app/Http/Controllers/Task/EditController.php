<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository\TaskRepositoryInterface;
use App\Service\Task\TaskValidationRulesServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;


#[Patch(
    path: '/api/tasks/{id}',
    description: 'Update Task',
    summary: 'Update Task',
    security: [['sanctum' => []]],
    requestBody: new RequestBody(
        description: 'Registration request',
        content:
        new JsonContent(
            ref: '#/components/schemas/TaskRequest',
        )
    ),
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
class EditController extends Controller
{

    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly TaskValidationRulesServiceInterface $taskValidationRulesServiceInterface
    ) {}

    /**
     * @throws ValidationException
     */
    public function edit(Request $request, int $id): JsonResponse
    {
        $currentUserId = Auth::user()->getAuthIdentifier();

        if (!$currentUserId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->only(['title', 'description', 'status']);

        $validationRules = $this->taskValidationRulesServiceInterface->getEditRules($data);

        $validator = Validator::make($data, $validationRules);

        if($validator->fails())
        {
            throw new ValidationException($validator);
        }

        $task = $this->taskRepository->update($id, $data, $currentUserId);

        if(is_null($task)){
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json(new TaskResource($task));
    }

}
