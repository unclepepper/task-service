<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * @return Collection
     */
    public function findAll(): Collection;

    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User;

    /**
     * @param int $id
     * @return User|null
     */
    public function getById(int $id): ?User;

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User;

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool;

}
