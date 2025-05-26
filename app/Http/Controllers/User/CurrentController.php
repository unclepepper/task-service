<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;


#[Get(
    path: '/api/user',
    description: 'Current user information',
    summary: 'Get information of user',
    security: [['sanctum' => []]],
    tags: ['Users'],
    responses: [
        new Response(
            response: 200,
            description: 'Success',
            content: new JsonContent(
                ref: '#/components/schemas/UserResource',
            )
        ),
        new Response(
            response: 401,
            description: 'Error: Unauthorized',
        ),
    ]
)]
class CurrentController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function current(): JsonResponse
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $user = $this->userRepository->getByIdOrFail($currentUser->id);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(new UserResource($user));
    }
}
