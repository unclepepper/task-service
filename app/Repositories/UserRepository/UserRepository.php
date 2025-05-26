<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly User $user
    )
    {}

    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }


    public function getByIdOrFail(int $id): User
    {
        $user = $this->user->find($id);
        if (!$user) {
            throw new ModelNotFoundException("User not found");
        }
        return $user;
    }

    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findAll(): Collection
    {
       return User::all();
    }

    public function update(User $user, array $data): User
    {
        if(!empty($data)){
            $user->update($data);
        }

        return $user;
    }

    public function updateRole(User $user, array $data): ?User
    {

        if($data['role'] ||
            array_key_exists($data['role'], User::ROLES)
        ){
            $role = User::ROLES[$data['role']];

            $user->update(['role' => $role]);

            return $user;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        return User::destroy($id) > 0;
    }

}
