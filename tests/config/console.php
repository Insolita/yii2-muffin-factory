<?php
return [
    'controllerMap'=>[
        'migrate'=>[
            'class'=>\yii\console\controllers\MigrateController::class,
            'migrationPath' => Yii::getAlias('@tests/migrations'),
            'db' => 'db'
        ],
    ]
];