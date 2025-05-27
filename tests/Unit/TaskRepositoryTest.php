<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Repositories\TaskRepository\TaskRepository;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository();

        User::factory()->create([
            'id' => 1,
            'role' => 1,
        ]);

        User::factory()->create([
            'id' => 999,
            'role' => 1,
        ]);

    }

   #[Test]
    public function test_find_all_by_user_id(): void
    {
        $userId = 1;

        Task::factory()->count(3)->create(['user_id' => $userId]);

        Task::factory()->create(['user_id' => 999]);

        $tasks = $this->taskRepository->findAllByUserId($userId);

        $this->assertCount(3, $tasks);
        foreach ($tasks as $task) {
            $this->assertEquals($userId, $task->user_id);
        }
    }

   #[Test]
    public function test_find_all_tasks_by_user_id_with_status()
    {
        $userId = 1;
        // Создаем задачи с разными статусами
        Task::factory()->count(2)->create(['user_id' => $userId, 'status' => 'pending']);
        Task::factory()->create(['user_id' => $userId, 'status' => 'completed']);

        $pendingTasks = $this->taskRepository->findAllByUserId($userId, 'pending');
        $completedTasks = $this->taskRepository->findAllByUserId($userId, 'completed');

        $this->assertCount(2, $pendingTasks);
        foreach ($pendingTasks as $task) {
            $this->assertEquals('pending', $task->status);
            $this->assertEquals($userId, $task->user_id);
        }

        $this->assertCount(1, $completedTasks);
        foreach ($completedTasks as $task) {
            $this->assertEquals('completed', $task->status);
            $this->assertEquals($userId, $task->user_id);
        }
    }

   #[Test]
    public function test_find_task_by_id_and_user_id()
    {
        $task = Task::factory()->create(['id' => 10, 'user_id' => 1]);


        $foundTask = $this->taskRepository->findById(10, 1);
        $this->assertNotNull($foundTask);
        $this->assertEquals($task->id, $foundTask->id);

        $notFound = $this->taskRepository->findById(10, 999);
        $this->assertNull($notFound);

        $notFound2 = $this->taskRepository->findById(9999, 1);
        $this->assertNull($notFound2);
    }

   #[Test]
    public function test_create_task()
    {
        // Передаем данные для создания
        $data = [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'status' => 'pending'
        ];

        $createdTask = $this->taskRepository->create($data, 1);

        $this->assertInstanceOf(Task::class, $createdTask);
        $this->assertEquals('Test Title', $createdTask->title);
        $this->assertEquals('Test Description', $createdTask->description);
        $this->assertEquals('pending',$createdTask ->status);
        $this ->assertEquals(1,$createdTask ->user_id);

        $this->assertDatabaseHas('tasks', [
            'id' => 1,
            'title' => 'Test Title'
        ]);
    }

   #[Test]
    public function test_update_task()
    {
        $task = Task::factory()->create([
            'id' => 20,
            'user_id' => 1,
            'title' => 'Old Title',
            'description' => 'Old description',
            'status' => 'pending'
        ]);

        $updateData = [
            'title' => 'New Title',
            'description' => 'New description',
            'status' => 'completed'
        ];

        $result =  $this -> taskRepository -> update(20,$updateData ,1);

        $this -> assertNotNull($result);
        $this -> assertEquals('New Title',$result -> title );
        $this -> assertEquals('completed',$result -> status );

        // Проверка в базе
        $this -> assertDatabaseHas('tasks', [
            'id'=>20,
            'title'=>'New Title',
            'description'=>'New description',
            'status'=>'completed'
        ]);

        // Попытка обновить задачу другого пользователя возвращает null
        $result2= $this -> taskRepository -> update(20,$updateData ,9999);
        $this -> assertNull($result2);

    }

   #[Test]
    public function test_returns_null_when_updating_nonexistent_task()
    {
        $result= $this -> taskRepository -> update(9999,['title'=>'X'],1 );
        $this -> assertNull($result);
    }



   #[Test]
    public function test_delete_task()
    {
        $task= Task::factory()->create();

        $deleted= $this -> taskRepository -> delete($task -> id);

        $this -> assertTrue($deleted);

        $this -> assertDatabaseMissing('tasks',['id'=>$task -> id]);

        // Попытка удалить несуществующую задачу возвращает false
        $deleted2= $this -> taskRepository -> delete(99999);
        $this -> assertFalse($deleted2);

    }
}
