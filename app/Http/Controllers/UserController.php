<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use Illuminate\Validation\ValidationException;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function users(): JsonResponse
    {
        return response()->json($this->userRepository->findAll());
    }

    public function current(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json($this->userRepository->getById($user->id));
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $user = $this->userRepository->getById($user->id);

        if (null === $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $data = $request->only(['name', 'email', 'password']);

        /** Генерация правил валидации */
        $validationRules = $this->getValidationRules($data);

        /** Валидация данных */
        $validator = Validator::make($data, $validationRules);

        if($validator->fails())
        {
            throw new ValidationException($validator);
        }

        try
        {

            $userUpdated = $this->userRepository->update($user, $data);

            return response()->json($userUpdated);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['message' => 'Task not found'], 404);
        }
        catch(ValidationException $e)
        {
            return response()->json(['errors' => $e->errors()], 400);
        }


    }

    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->getById($id);

        if (null === $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->userRepository->delete($user);

        return response()->json(null, 204);
    }

    private function getValidationRules(array $data): array
    {
        $rules = [];

        if(isset($data['email']))
        {
            $rules['email'] = 'required|string|email';
        }

        if(isset($data['name']))
        {
            $rules['name'] = 'required|string';
        }

        if(isset($data['password']))
        {
            $rules['password'] = 'required|string';
        }

        return $rules;
    }
}
