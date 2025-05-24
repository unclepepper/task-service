<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\UserRepository\UserRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    public function test_create_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $user = $this->userRepository->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertTrue(\Hash::check($data['password'], $user->password));
    }

    public function test_get_by_id()
    {
        $user = User::factory()->create();

        $foundUser = $this->userRepository->getById($user->id);

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_get_by_email()
    {
        $user = User::factory()->create(['email' => 'unique@example.com']);

        $foundUser = $this->userRepository->getByEmail('unique@example.com');

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_find_all()
    {
        User::factory()->count(3)->create();

        $users = $this->userRepository->findAll();

        $this->assertCount(3, $users);
    }

    public function test_update_user()
    {
        $user = User::factory()->create(['name' => 'Old Name']);

        $updatedUser = $this->userRepository->update($user, ['name' => 'New Name']);

        $this->assertEquals('New Name', $updatedUser->name);

        // Проверяем, что изменения сохранились в базе
        $freshUser = User::find($user->id);
        $this->assertEquals('New Name', $freshUser->name);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create();

        $result = $this->userRepository->delete($user);

        $this->assertTrue($result);

        // Проверяем, что пользователь удален из базы
        $this->assertNull(User::find($user->id));
    }
}
