<?php

declare(strict_types=1);

namespace App\Repositories\TaskRepository;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    /**
     * @param int $userId
     * @param string|null $status
     * @return Collection
     */
    public function findAllByUserId(int $userId, ?string $status = null): Collection;

    /**
     * @param int $id
     * @param int $userId
     * @return Task|null
     */
    public function findById(int $id, int $userId): ?Task;

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
