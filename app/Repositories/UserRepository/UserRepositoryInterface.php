<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function getByEmail(string $email): ?User;
}
