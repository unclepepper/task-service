<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Response;


class CurrentController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    #[Get(
        path: '/api/user/current',
        description: 'Current user information',
        summary: 'Get information of user',
        security: [['bearer_token' => []]],
        tags: ['User'],
        responses: [
            new Response(
                response: 200,
                description: 'Get information of user',
                content: new JsonContent(
                    ref: '#/components/schemas/UserResource',
                )
            )
        ]

    )]
    public function current(Request $request): JsonResponse
    {
        $currentUser = $request->user();

        $user = $this->userRepository->getById($currentUser->id);

        if(null === $user) {
            throw new ModelNotFoundException('User not found');
        }

        return response()->json(new UserResource($user));
    }
}
