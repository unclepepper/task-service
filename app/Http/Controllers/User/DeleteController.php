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
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;


class DeleteController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}


    #[Delete(
        path: '/api/users/{id}',
        description: 'Get collection of users',
        summary: 'Get collection of users',
//        security: [['bearer_token' => []]],
        tags: ['User'],
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
            )
        ]

    )]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->getById($id);

        if (null === $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->userRepository->delete($user);

        return response()->json(['message' => sprintf('The user id= %d was successfully deleted', $id)]);
    }

}
