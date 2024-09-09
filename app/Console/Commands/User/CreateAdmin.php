<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    // Название команды
    protected $signature = 'make:admin {name} {email} {password}';

    // Описание команды
    protected $description = 'Создает администратора';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = Hash::make($this->argument('password')); // Хешируем пароль

        // Проверка, существует ли пользователь с таким email
        if (User::where('email', $email)->exists()) {
            $this->error('Пользователь с таким email уже существует.');
            return;
        }

        // Создаем нового пользователя с ролью 'admin'
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'status' => User::STATUS_ACTIVE,
            // 'role' => User::ROLE_ADMIN,
        ]);

        $this->info('Администратор успешно создан!');
    }
}
