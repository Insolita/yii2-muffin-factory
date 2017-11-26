<?php
return [
    'controllerMap'=>[
        'migrate'=>[
            'class'=>\yii\console\controllers\MigrateController::class,
            'migrationPath' => '@tests/migrations',
            'db' => 'db'
        ],
    ]
];