<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;


#[Get(
    path: '/api/users/current',
    description: 'Current user information',
    summary: 'Get information of user',
    security: [['sanctum' => []]],
    tags: ['Users'],
    responses: [
        new Response(
            response: 200,
            description: 'Get information of user',
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

    public function current(Request $request): JsonResponse
    {
        $currentUser = $request->user();

        $user = $this->userRepository->getById($currentUser->id);

        return response()->json(new UserResource($user));
    }
}
