<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RssChannel */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Rss каналы', 'url' => '/admin/rss-channel'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="rss-channel-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
