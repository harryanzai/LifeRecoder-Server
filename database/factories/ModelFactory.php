<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// 参考链接
//http://stackoverflow.com/questions/34496720/change-faker-locale-in-laravel-5-2
$localFaker = \Faker\Factory::create('zh_CN');
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) use($localFaker) {
    static $password;


    return [
        'nickname' => $localFaker->name,
        'email' => $localFaker->unique()->safeEmail,
        'mobile' => $localFaker->unique()->phoneNumber,
        'avatar' => $localFaker->imageUrl('400','400'),
        'last_login_time' => $localFaker->dateTimeBetween('-1 years','now'),
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});




