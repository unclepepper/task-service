<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository\UserRepository;
use App\Service\User\UserValidationRulesServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

#[Patch(
    path: '/api/users',
    description: 'Edit users',
    summary: 'Edit users',
    security: [['sanctum' => []]],
    requestBody: new RequestBody(
        description: 'Registration request',
        content:
        new JsonContent(
            ref: '#/components/schemas/RegisterRequest',
        )
    ),
    tags: ['Users'],
    responses: [
        new Response(
            response: 200,
            description: 'Edit users',
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
class EditController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserValidationRulesServiceInterface $userValidationRulesService,
    ) {}


    /**
     * @throws ValidationException
     */
    public function edit(Request $request): JsonResponse
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

        $data = $request->only(['name', 'email', 'password']);

        /** Получение правил валидации через сервис */
        $validationRules = $this->userValidationRulesService->getEditRules($data);

        $validator = Validator::make($data, $validationRules);

        if($validator->fails())
        {
            throw new ValidationException($validator);
        }

        $userUpdated = $this->userRepository->update($user, $data);

        return response()->json(new UserResource($userUpdated));

    }

}
