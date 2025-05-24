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


class IndexController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->userRepository->findAll());
    }
}
