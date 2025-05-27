<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRoleRequest;
use App\Http\Resources\UserRoleResource;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

#[Patch(
    path: '/api/users/role/{id}',
    description: 'Update user role',
    summary: 'Update user role',
    security: [['sanctum' => []]],
    requestBody: new RequestBody(
        description: 'Request',
        content:
        new JsonContent(
            ref: '#/components/schemas/UserRoleRequest',
        )
    ),
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
            response: 200,
            description: 'Success',
            content: new JsonContent(
                ref: '#/components/schemas/UserRoleResource',
            )
        ),
        new Response(
            response: 401,
            description: 'Error: Unauthorized',
        ),
    ]
)]
class UserRoleController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}


    /**
     * @param UserRoleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function editRole(UserRoleRequest $request, int $id): JsonResponse
    {
        try
        {
            $user = $this->userRepository->getByIdOrFail($id);
        }
        catch(ModelNotFoundException)
        {
            return response()->json(['message' => 'User not found'], 404);
        }

        $data = $request->validated();

        $userUpdated = $this->userRepository->updateRole($user, $data);

        if(is_null($userUpdated)){
            return response()->json(['message' => 'User update failed'], 500);
        }

        return response()->json(new UserRoleResource($userUpdated));

    }
}
