<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleRating */

$this->title = 'Обновить ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => '/admin/banner'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-rating-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
