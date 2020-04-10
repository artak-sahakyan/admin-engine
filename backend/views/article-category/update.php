<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleCategory */

$this->title = 'Изменить категорию: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => '/admin/article-category'];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => '/admin/article-category/view?id=' . $model->id];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
