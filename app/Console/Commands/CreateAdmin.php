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
        if (User::where('email', self::ADMIN_EMAIL)->exists()) {
            $this->error('Пользователь с таким email уже существует.');
            return 1;
        }

       $admin = $this->userRepository->create([
           'name'       => self::ADMIN_NAME,
           'email'      => self::ADMIN_EMAIL,
           'password'   => self::ADMIN_PASSWORD,
           'role'       => User::ROLE_ADMIN,
       ]);

        $this->info(sprintf('Администратор с именем %s успешно создан!', $admin->name));
        $this->warn(sprintf('Email: %s', $admin->email));
        $this->warn(sprintf('Password: %s', self::ADMIN_PASSWORD));


        return 0;
    }
}
