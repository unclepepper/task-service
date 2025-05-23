<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;
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

    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
