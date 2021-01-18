Yii2 Muffin Factory
===================
 A Port of laravel factory for generate fixtures on fly and database seeding

 

Installation
============
Either run

```
composer require -dev insolita/yii2-muffin-factory:~2.0.0
```
or add

```
"insolita/yii2-muffin-factory": "~2.0.0"
```
in require-dev section of your `composer.json` file.

Configure
---------

Add in bootstrap for test suite (or in every app, where it can be used)

```php
//with default factory path by alias @tests/factories
Yii::$container->setSingleton(
     \insolita\muffin\Factory::class,
      [],
     [\Faker\Factory::create('en_EN')]
  );

//with custom factory path
Yii::$container->setSingleton(
     \insolita\muffin\Factory::class,
     [],
     [
         \Faker\Factory::create('ru_RU'),  //Faker language
          '@common/data/factories'         // Custom directory for factories
     ]
 );
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

See more examples in [FactoryTest](tests/unit/FactoryTest.php)

Use with ActiveFixtures
=======================
Create ActiveFixture extended classed in configured directory as usual

```php

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;

    protected function getData()
    {
        return array_merge(
            factory(User::class, 1)->states('developer')->raw(),
            factory(User::class, 10)->states('client')->raw()
        );
    }
}
```

*Fixtures with relation dependency*

```php
class PostFixture extends ActiveFixture
{
    public $modelClass = Post::class;
    public $depends = [UserFixture::class];

    public function load()
    {
        $this->data = [];
        $users = User::find()->select(['id'])->column();
        foreach ($users as $userId) {
            $posts = factory(Post::class, 5)->create(['createdBy' => $userId]);
            foreach ($posts as $post) {
                $this->data[$post->id] = $post->getAttributes();
            }
        }
        return $this->data;
    }
}

class SomeFixture extends ActiveFixture
{
    public $modelClass = Some::class;
    public $depends = [UserFixture::class];

    protected function getData()
    {
        $users = User::find()->select(['id'])->where(['status'=>'client'])->column();
        $developer = User::find()->where(['status'=>'developer'])->limit(1)->one();
        $data =  array_merge(
            factory(Some::class, 5)->raw(['user_id'=>ArrayHelper::random($users)]),
            factory(Some::class, 20)->states('one')->raw(['user_id'=>function() use(&$users){ return ArrayHelper::random($users);}]),
            factory(Some::class, 11)->states('two')->raw(['user_id'=>$developer->id])
        );
        return $data;
    }
}
```