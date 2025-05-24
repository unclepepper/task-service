<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class CurrentController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}


    public function current(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json($this->userRepository->getById($user->id));
    }
}
