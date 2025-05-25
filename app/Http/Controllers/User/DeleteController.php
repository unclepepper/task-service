<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;

#[Delete(
    path: '/api/users/{id}',
    description: 'Get collection of users',
    summary: 'Get collection of users',
    security: [['sanctum' => []]],
    tags: ['Users'],
    parameters: [
        new Parameter(
            name: 'id',
            description: 'User ID',
            in: 'path',
            required: true,
        )
    ],
    responses: [
        new Response(
            response: 204,
            description: 'User deleted',
            content: null
        ),
        new Response(
            response: 404,
            description: 'User not found',
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
        private readonly UserRepository $userRepository,
    ) {}


    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->getById($id);

        if (null === $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->userRepository->delete($user);

        return response()->json(null,204);
    }

}
