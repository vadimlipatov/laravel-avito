<?php

use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    $active = $faker->boolean;
    $phoneActive = $faker->boolean;
    return [
        'name' => $this->faker->name,
        'last_name' => $faker->lastName,
        'email' => $this->faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber,
        'phone_verified' => $phoneActive,
        'phone_verify_token' => $phoneActive ? null : Str::uuid(),
        'phone_verify_token_expire' => $phoneActive ? null : Carbon::now()->addSeconds(300),
        'password' => '$2y$10$aymrH9UbPv/ccDOnJbu74eTgwtr3oR8GCvvjZMMlHT9GIJIPV5SEi', // secret
        'remember_token' => str_random(10),
        'verify_token' => $active ? null :  Str::uuid(),
        'role' => $active ? $faker->randomElement([User::ROLE_USER, User::ROLE_ADMIN]) : User::ROLE_USER,
        'status' => $active ? User::STATUS_ACTIVE : User::STATUS_WAIT,
    ];
});
