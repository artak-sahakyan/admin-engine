<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = 'Обновить';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => '/admin/article'];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => '/admin/article/view?id=' . $model->id];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-update">

    <?= $this->render('_form', compact('model', 'articleMeta', 'articleBannerGroup', 'modelVoting')) ?>

</div>
