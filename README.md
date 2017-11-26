Yii2 Muffin Factory
===================
 Port of laravel factory for data seeding

[![Build Status](https://travis-ci.org/Insolita/yii2-muffin.svg?branch=master)](https://travis-ci.org/Insolita/yii2-muffin)

Installation
============
Either run

```
composer require -dev insolita/yii2-muffin:~0.0.1
```
or add

```
"insolita/yii2-muffin": "~0.0.1"
```
in require-dev section of your `composer.json` file.

Configure
---------

Add in bootstrap

```
php
//with default factory path by alias @tests/factories
Yii::$container->setSingleton(\insolita\muffin\Factory::class, [], [\Faker\Factory::create('en_EN')]);

//with custom factory path
Yii::$container->setSingleton(\insolita\muffin\Factory::class, [], [\Faker\Factory::create('en_EN'), '@common/data/factories']);

require(Yii::getAlias('@vendor/insolita/yii2-muffin/src/helpers.php'));
```

Create Factories
================
You can create all factories in single file, or in individual files in directory defined in factory configuration

example UserFactory.php

```php
/**
 * @var \insolita\muffin\Factory $factory
 **/

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

```

Use Factories
=============

*Populate new record without saving*

```php
 /**@var User $user **/
$user = factory(User::class)->make();
$user = factory(User::class)->states('client')->make();
 /**@var User[] $user **/
$users = factory(User::class, 5)->make();
$users = factory(User::class, 5)->states(['client', 'developer'])->make();
```

*Populate and persist records*


```php

 /**@var User $user * */
$user = factory(User::class)->create();
 /**@var User[] $user **/
$users = factory(User::class, 10)->states(['client'])->create(['registered'=>Carbon::now()]);
```

