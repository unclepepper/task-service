<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TaskIndexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_tasks_by_user()
    {
        // Создаем пользователя
        $user = User::factory()->create();

        // Создаем задачи для этого пользователя
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);
        $response->assertJsonCount(3 );

        foreach ($response->json() as $task) {
            $this->assertEquals($user->id, $task['user_id']);
        }
    }
}
