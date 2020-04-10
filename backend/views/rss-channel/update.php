<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RssChannel */

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Rss каналы', 'url' => '/admin/rss-channel'];
$this->params['breadcrumbs'][] = ['label' => $model->alias, 'url' => '/admin/rss-channel/view?id=' . $model->id];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="rss-channel-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
