<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleCategory */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => '/admin/article-category'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
