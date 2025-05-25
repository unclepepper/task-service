<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;


#[Post(
    path: '/api/login',
    description: 'Get Bearer token',
    summary: 'Login user',
    requestBody: new RequestBody(
        description: 'Login request',
        content:
        new JsonContent(
            ref: '#/components/schemas/LoginRequest',
        )
    ),
    tags: ['Authentication'],
    responses: [
        new Response(
            response: 200,
            description: 'Get Bearer token',
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
class LoginController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}


    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|string|email|max:50|',
            'password' => 'required|string|required|min:3',
        ]);

        $user = $this->userRepository->getByEmail($data['email']);

        if(null === $user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json( new AuthResource($token));
    }
}
