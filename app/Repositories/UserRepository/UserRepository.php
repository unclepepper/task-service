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
        $role = 1;

        if($data['role']){
            $role = $data['role'];
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
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

    public function delete(int $id): bool
    {
        return User::destroy($id) > 0;
    }

}
