<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $guest;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем пользователя-администратора (role = 2)
        $this->admin = User::factory()->create([
            'role' => 2,
        ]);

        // Создаем обычного пользователя (role = 1)
        $this->user = User::factory()->create([
            'role' => 1,
        ]);
    }

    #[Test]
    public function test_admin_access_users_list()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->get('/api/users');

        $response->assertStatus(200);
    }

    #[Test]
    public function test_user_cannot_access_users_list()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->get('/api/users');

        $response->assertStatus(403);
        $response->assertJson(['error' => 'Access denied']);
    }

    #[Test]
    public function test_admin_can_delete_user()
    {
        $targetUser = User::factory()->create(['role' => 1]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->delete('/api/users/' . $targetUser->id);

        $response->assertStatus(204);

        // Проверка, что пользователь удален из базы
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    #[Test]
    public function test_user_cannot_delete_user()
    {
        $targetUser = User::factory()->create(['role' => 1]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->delete('/api/users/' . $targetUser->id);

        $response->assertStatus(403);

        $response->assertJson(['error' => 'Access denied']);
    }
}

