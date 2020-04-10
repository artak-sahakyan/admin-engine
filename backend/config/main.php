<?php

use \common\models\AdminGroup;

$params = array_merge(
    require ENGINE_PATH.'/common/config/params.php',
   // require ENGINE_PATH.'/common/config/params-local.php',
    require __DIR__ . '/params.php'
  //  require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
        ],
        'gridview' => [
           'class' => 'kartik\grid\Module',
        ]
    ],
    'components' => [
         // 'cache' => [
         //     'class' => 'yii\caching\FileCache',
         //     'cachePath' => Yii::getAlias('@siteFrontend') . '/runtime/cache'
         // ],
        // 'db' => [
        //     'schemaCache' => 'cache',
        //     'schemaCacheDuration' => 3600,
        //     'enableSchemaCache' => true,
        // ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin'
        ],
        'user' => [
            'identityClass' => 'common\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'article/index', //@todo временная мера, пока не сделаем дашбоард
            ],
        ],
    ],
    'on beforeAction' => function($event) {
        $controller = $event->action->controller->id;
        $action = $event->action->id;

        // allow all login page
        $allowPages = [
            'site' => [
                'login' => true,
                'logout' => true,
                'error' => true,
            ],
            'article' => [
                'upload-images' => true
            ]
        ];
        if (\Yii::$app->user->isGuest || !empty($allowPages[$controller][$action])) {
            if ($controller != 'site' && $action != 'login') {
                // force show login page
                return \Yii::$app->response->redirect('/admin/site/login');
            }

            return '';
        }

        $userHaveGroups = \Yii::$app->user->identity->getAdminGroups()->asArray()->all();
        $allowActions = AdminGroup::getAllowActions($userHaveGroups);

        // access close
        if (!AdminGroup::isAdmin()) {
            if (empty($allowActions[$controller][$action])) {
                throw new \yii\web\ForbiddenHttpException;
            }
        }
    },
    'params' => $params,
];
