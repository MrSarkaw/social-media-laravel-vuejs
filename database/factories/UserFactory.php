<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\chats;
use App\likePost;
use App\posts;
use App\follow;
use App\comment;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$w//2IBrQKE4q9O9NpL7OZeG3UoWGC7AHphEgTNaXygXuytTaW4.dO', // password
    ];
});

$factory->define(likePost::class, function (Faker $faker) {

    return [
        'user_id' =>rand(5,104),
        'post_id'=>'5',
        
    ];
});
