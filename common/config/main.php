<?php
return [
    'language' => 'ru',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@frontend' => '../../frontend/web/',
        '@device_id' => 1
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
         'class' => 'yii\caching\FileCache',
         'cachePath' => Yii::getAlias('@siteFrontend') . '/runtime/cache'
        ],
         'log' => [
            'targets' => [
                [
                    'class' => 'common\helpers\ErrorLogger',
                    'levels' => ['error', 'warning'],
                    'exportInterval' => 1,
                ],
            ],
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],
    ],
    'bootstrap' => [
        'queue',
    ],
];
