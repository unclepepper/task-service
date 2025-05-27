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
    description: 'Deleting a user',
    summary: 'Deleting a user',
    security: [['sanctum' => []]],
    tags: ['Users'],
    parameters: [
        new Parameter(
            name: 'id',
            description: 'User Id',
            in: 'path',
            required: true,
            example: 1
        )
    ],
    responses: [
        new Response(
            response: 204,
            description: 'Success',
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
        if ($this->userRepository->delete($id)) {
            return response()->json(['message' => 'Deleted successfully'], 204);
        }

        return response()->json(['message' => 'User not found'], 404);
    }


}
