<?php

namespace App\Repositories\TaskRepository;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    /**
     * @param int $userId
     * @return Collection
     */
    public function findAllByUserId(int $userId): Collection;


    public function create(array $data, int $user): Task;

    /**
     * @param int $id
     * @param array $data
     * @param int $userId
     * @return Task|null
     */
    public function update(int $id, array $data, int $userId): ?Task;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
