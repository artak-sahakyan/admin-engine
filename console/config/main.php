<?php
$params = array_merge(

    require __DIR__ . '/../../common/config/params.php',
   // require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php'
   // require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],

        'global-migrate' => [
            'class' => 'console\controllers\GlobalMigrateController',
            // 'migrationPath' => [ROOT_PATCH . '/resources/cli/migrations'],
            'migrationPath' => [__DIR__ . '/../migrations'],
            'migrationTable'=>'{{system_migrations}}',
            'interactive' => false,
        ],

        'global-command' => [
            'class' => 'console\controllers\GlobalCommandController',
          //  'migrationPath' => [__DIR__ . '/../controllers'],
           // 'migrationTable'=>'{{system_cmd}}',
           // 'interactive' => false,
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
