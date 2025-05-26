<?php

declare(strict_types=1);

namespace App\Repositories\TaskRepository;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function findAllByUserId(int $userId): Collection
    {
        return Task::where(['user_id' => $userId])->get();
    }

    public function findById(int $id, int $userId): ?Task
    {
        return Task::where([
            'id' => $id,
            'user_id' => $userId
        ])->first();
    }


    public function create(array $data, int $user): Task
    {
        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'status' =>  $data['status'],
            'user_id' => $user
        ]);

    }

    public function update(int $id, array $data, int $userId): ?Task
    {
        $task = Task::where([
            'id' => $id,
            'user_id' => $userId
        ])->first();

        if($task && !empty($data)){
            $task->update($data);
            return $task;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        return Task::destroy($id) > 0;
    }
}
