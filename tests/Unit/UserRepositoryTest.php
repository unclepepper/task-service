<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\Attributes\Test;
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
        $this->userRepository = new UserRepository(new User());
    }

    #[Test]
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


    #[Test]
    public function test_get_user_by_id()
    {
        $user = User::factory()->create();

        $foundUser = $this->userRepository->getByIdOrFail($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

   #[Test]
    public function test_exception_when_user_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        // Попытка найти несуществующего пользователя
        $this->userRepository->getByIdOrFail(99999);
    }

    #[Test]
    public function test_get_by_email()
    {
        $user = User::factory()->create(['email' => 'unique@example.com']);

        $foundUser = $this->userRepository->getByEmail('unique@example.com');

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    #[Test]
    public function test_find_all()
    {
        User::factory()->count(3)->create();

        $users = $this->userRepository->findAll();

        $this->assertCount(3, $users);
    }

    #[Test]
    public function test_update_user()
    {
        $user = User::factory()->create(['name' => 'Old Name']);

        $updatedUser = $this->userRepository->update($user, ['name' => 'New Name']);

        $this->assertEquals('New Name', $updatedUser->name);

        $freshUser = User::find($user->id);
        $this->assertEquals('New Name', $freshUser->name);
    }

    #[Test]
    public function test_delete_user()
    {
        $user = User::factory()->create();

        $result = $this->userRepository->delete($user->id);

        $this->assertTrue($result);

        // Проверяем, что пользователь удален из базы
        $this->assertNull(User::find($user->id));
    }
}
