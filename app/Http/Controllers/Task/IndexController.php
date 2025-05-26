<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


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

        $tasks = $this->taskRepository->findAllByUserId($currentUserId);

        if (request()->has('status')) {
            $tasks->where('status', request()->status);
        }

        return response()->json(TaskResource::collection($tasks));
    }
}
