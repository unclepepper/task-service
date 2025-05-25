<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;

#[Get(
    path: '/api/users',
    description: 'Get collection of users',
    summary: 'Get collection of users',
    security: [['sanctum' => []]],
    tags: ['Users'],
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
        ),
        new Response(
            response: 401,
            description: 'Error: Unauthorized',
        ),
    ]
)]
class IndexController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}


    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return response()->json(
            UserResource::collection($users)
        );
    }
}
