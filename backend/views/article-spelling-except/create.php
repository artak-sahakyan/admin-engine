<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSpellingExcept */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Орфографические ошибки', 'url' => '/admin/article-spelling'];
$this->params['breadcrumbs'][] = ['label' => 'Слова-исключения', 'url' => '/admin/article-spelling-except'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-spelling-except-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
