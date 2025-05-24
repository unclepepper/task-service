<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{

    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function getById(int $id): ?User
    {
        return User::where('id', $id)->first();
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

    public function delete(User $user): bool
    {
        $user->delete();

        return true;
    }

}
