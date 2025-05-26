<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Console\Command;


class CreateAdmin extends Command
{

    /**
     * Create user with admin role.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    protected $description = 'Create admin';

    private const string ADMIN_NAME = 'admin';

    private const string ADMIN_EMAIL = 'admin@admin.com';

    private const string ADMIN_PASSWORD = 'password';

    public function __construct(
        private UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    /**
     * Выполнение команды.
     *
     * @return int
     */
    public function handle()
    {
        // Запрос данных у пользователя
        $name = 'admin';
        $email = 'admin@admin.com';
        $password = 'password';

        if (User::where('email', $email)->exists()) {
            $this->error('Пользователь с таким email уже существует.');
            return 1;
        }

       $user = $this->userRepository->create([
           'name' => $name,
           'email' => $email,
           'password' => bcrypt($password),
           'role' => User::ROLE_ADMIN,
       ]);

        $this->info(sprintf('Администратор с именем %s успешно создан!', $user->name));
        $this->warn(sprintf('Email: %s', $user->email));
        $this->warn(sprintf('Password: %s', self::ADMIN_PASSWORD));


        return 0;
    }
}
