<?php

use App\Entity\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    $status = $faker->randomElement([User::STATUS_ACTIVE, User::STATUS_WAIT]);

    return [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'password' => '$2y$10$aymrH9UbPv/ccDOnJbu74eTgwtr3oR8GCvvjZMMlHT9GIJIPV5SEi', // secret
        'verify_token' => $status === User::STATUS_ACTIVE ? null :  Str::uuid(),
        'remember_token' => str_random(10),
        'status' => $status,
    ];
});
