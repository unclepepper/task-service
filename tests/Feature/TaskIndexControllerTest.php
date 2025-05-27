<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;

class TaskIndexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_tasks_by_status()
    {
        $user = User::factory()->create();

        Task::factory()->count(2)->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
        Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/tasks?status=pending');

        $response->assertStatus(200);

        $tasks = $response->json();

        foreach ($tasks as $task) {
            $this->assertEquals('pending', $task['status']);
        }

        $this->assertCount(2, $tasks);
    }
}
