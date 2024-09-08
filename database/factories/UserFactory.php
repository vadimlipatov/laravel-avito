<?php

use App\Entity\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $this->faker->name, // Генерирует случайное имя
        'email' => $this->faker->unique()->safeEmail, // Генерирует уникальный email
        'status' => $this->faker->randomElement(['active', 'wait']), // Генерирует случайный статус
        'password' => bcrypt('password'), // Установите пароль (можно изменить на другой)
    ];
});
