<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('ENGINE_PATH') or define('ENGINE_PATH', dirname(__DIR__, 4) . '/engine');

require ENGINE_PATH.'/vendor/autoload.php';
require ENGINE_PATH.'/vendor/yiisoft/yii2/Yii.php';
require ENGINE_PATH.'/common/config/bootstrap.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require ENGINE_PATH.'/common/config/main.php',
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require ENGINE_PATH.'/backend/config/main.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

(new yii\web\Application($config))->run();
