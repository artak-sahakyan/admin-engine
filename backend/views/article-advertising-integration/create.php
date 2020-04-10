<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleAdvertisingIntegration */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Рекламные интеграции', 'url' => '/admin/article-advertising-integration'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-advertising-integration-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
