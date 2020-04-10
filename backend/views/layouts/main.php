<?php
use yii\helpers\Html; 
use common\models\{ ErrorLog, Queue };

/* @var $this \yii\web\View */
/* @var $content string */

$sidebarCollapseRoutes = [
    'article/update',
    'article/create'
];

$errorLogs = ErrorLog::find()->select('category')->orderBy('log_time DESC')->asArray()->all();
$queueCount = Queue::find()->count();
$mustCollapsed = in_array(Yii::$app->requestedRoute, $sidebarCollapseRoutes);

if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    backend\assets\AppAsset::register($this);
    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body class="hold-transition <?=$mustCollapsed ? 'sidebar-collapse' : '' ?> skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header',
            ['directoryAsset' => $directoryAsset, 'errorLogs' => $errorLogs, 'queueCount' => $queueCount]
        ) ?>

            <?= $this->render(
                'left',
                ['directoryAsset' => $directoryAsset]
            )
            ?>

            <?= $this->render(
                'content',
                ['content' => $content, 'directoryAsset' => $directoryAsset]
            ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
