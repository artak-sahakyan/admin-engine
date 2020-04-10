<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleAdvertisingIntegration */

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Рекламные интеграции', 'url' => '/admin/article-advertising-integration'];
$this->params['breadcrumbs'][] = ['label' => $this->title . ': ' . $model->id];
?>
<div class="article-advertising-integration-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
