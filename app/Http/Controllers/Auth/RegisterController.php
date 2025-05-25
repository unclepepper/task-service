<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Repositories\UserRepository\UserRepository;
use App\Service\User\UserValidationRulesServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;


#[Post(
    path: '/api/register',
    summary: 'Registration new user',
    requestBody: new RequestBody(
        description: 'Registration request',
        content:
        new JsonContent(
            ref: '#/components/schemas/RegisterRequest',
        )
    ),
    tags: ['Authentication'],
    responses: [
        new Response(
            response: 201,
            description: 'Create new user and get bearer token',
            content: new JsonContent(
                ref: '#/components/schemas/AuthResource',
            )
        ),
        new Response(
            response: 422,
            description: 'Error: Unprocessable Entity',
        )
    ],
)]
class RegisterController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserValidationRulesServiceInterface $userValidationRulesService,
    ) {}


    public function register(Request $request): JsonResponse
    {
        $data = $this->userValidationRulesService->getRegisterRules($request);

        if(null !== $this->userRepository->getByEmail($data['email'])){
            return response()->json(["message" => "User already exists"], 400);
        }

        $user = $this->userRepository->create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(new AuthResource($token), 201);
    }

}
