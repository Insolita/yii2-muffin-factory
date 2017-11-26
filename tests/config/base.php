<?php
use insolita\muffin\Factory;

Yii::setAlias('@tests', dirname(__DIR__).'/tests');
Yii::$container->setSingleton(Factory::class, [], [\Faker\Factory::create('ru_RU')]);

return [
    'id' => 'app-test',
    'basePath' => dirname(dirname(__DIR__)),
    'sourceLanguage' => 'en-US',
    'timeZone'            => 'Europe/Moscow',
    'language'       => 'ru',
    'charset'        => 'utf-8',
    'bootstrap'=>['log'],
    'components'=>[
        'db'=>[
            'class'=>\yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;port=3306;dbname=yii2_ext_test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'test_'
        ]
    ],
    'modules'=>[
    ],
    'params'=>[
    
    ]
];