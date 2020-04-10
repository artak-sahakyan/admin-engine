<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => '/admin/article'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-create">

    <?= $this->render('_form', [
        'model'         => $model,
        'articleMeta'   => $articleMeta,
        'articleBannerGroup' => $articleBannerGroup
    ]) ?>

</div>
