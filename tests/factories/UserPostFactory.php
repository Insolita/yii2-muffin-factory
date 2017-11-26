<?php
/**
 * @var \insolita\muffin\Factory $factory
 **/

use tests\stubs\Post;
use tests\stubs\User;

$factory->define(User::class, function (\Faker\Generator $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'lastName' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'status'=>'default',
        'passwordHash' => $password ?: $password = Yii::$app->security->generatePasswordHash('secret'),
        'authKey' => Yii::$app->security->generateRandomString(),
        'accessToken' => Yii::$app->security->generateRandomString(64),
        'birthday' => $faker->date('Y-m-d', '-15 years'),
        'registered' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
    ];
});
$factory->state(User::class, 'developer', [
    'status' => 'developer',
]);
$factory->state(User::class, 'client', [
    'status' => 'client',
]);

$factory->define(Post::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(),
        'body' => $faker->text(),
        'cover' => $faker->imageUrl(),
        'createdBy' => function () {
            return factory(User::class)->states('client')->create()->id;
        },
        'createdAt' => $faker->dateTime()->format('Y-m-d H:i:s'),
        'updatedAt' => $faker->dateTime()->format('Y-m-d H:i:s'),
    ];
});


