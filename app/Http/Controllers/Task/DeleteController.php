<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;

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
