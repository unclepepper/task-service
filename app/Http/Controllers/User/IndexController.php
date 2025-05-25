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
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\Response;


class IndexController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}


    #[Get(
        path: '/api/users',
        description: 'Get collection of users',
        summary: 'Get collection of users',
        security: [['bearer_token' => []]],
        tags: ['User'],
        responses: [
            new Response(
                response: 200,
                description: 'Get collection of users',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(
                        ref: '#/components/schemas/UserResource',
                    )
                )
            )
        ]

    )]
    public function index(): JsonResponse
    {
        return response()->json(
            UserResource::collection(
                new UserResource($this->userRepository->findAll())
            )
        );
    }
}
