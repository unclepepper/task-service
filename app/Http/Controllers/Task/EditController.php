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
